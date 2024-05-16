<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\imagePreview;

use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Utils;
use Exception;
/**
 * Provide a file system cache. It is abstract as you should implement your own class for this with missing methods.
 * @internal
 */
abstract class FsImagePreviewCache extends ImagePreviewCache
{
    const MAX_DOWNLOAD_SIZE = 3145728;
    private $absolutePath;
    private $prefixUrl;
    private $invalidateSeconds;
    /**
     * C'tor.
     *
     * @param string $absolutePath Needs to end with `/`
     * @param string $prefixUrl Needs to end with `/`, there the cached file name gets appended
     * @param int $invalidateSeconds
     * @throws Exception Absolute path is not writable or does not exist
     */
    public function __construct($absolutePath, $prefixUrl, $invalidateSeconds = 0)
    {
        if (!\is_writable($absolutePath)) {
            throw new Exception(\sprintf('%s is not writable or does not exist!', $absolutePath));
        }
        $this->absolutePath = $absolutePath;
        $this->prefixUrl = $prefixUrl;
        $this->invalidateSeconds = $invalidateSeconds;
    }
    /**
     * See `ImagePreviewCache`.
     *
     * @param Thumbnail[] $thumbnails Key is the embed URL
     */
    public function get($thumbnails)
    {
        foreach ($thumbnails as $thumbnail) {
            $files = $this->getCachedFilesForUrl($thumbnail->getId());
            // No cache found, let's fetch again...
            if (\count($files) === 0) {
                continue;
            }
            $file = $files[0];
            $filename = \basename($file);
            // Check if file is older than x seconds and invalidate it (download again)
            if ($this->invalidateSeconds > 0 && \time() - \filemtime($file) > $this->invalidateSeconds) {
                continue;
            }
            if (Utils::endsWith($filename, '.failed')) {
                $thumbnail->setError('Failed in a previous download, lets wait until cache gets invalidated.');
                continue;
            }
            $metadata = $this->getCachedMetadata($filename);
            if ($metadata === null) {
                $thumbnail->setError('Failed to obtain metadata JSON file.');
                continue;
            }
            $thumbnail->setMd5File(\explode('-', \explode('.', $filename, 2)[0], 2)[1]);
            $thumbnail->setCacheUrl($this->prefixUrl . $filename);
            $thumbnail->setWidth($metadata['width']);
            $thumbnail->setHeight($metadata['height']);
            $thumbnail->setTitle($metadata['title']);
            if ($thumbnail->getForceRatio() === null) {
                $thumbnail->setForceRatio($metadata['forceRatio'] ?? null);
            }
        }
    }
    /**
     * See `ImagePreviewCache`.
     *
     * @param Thumbnail[] $thumbnails Key is the embed URL
     */
    public function set($thumbnails)
    {
        $mh = \curl_multi_init();
        $handles = [];
        foreach ($thumbnails as $embedUrl => $thumbnail) {
            // Clear fail-files but keep all other files to not break browser caches or reproductions (like RCB List of consents)
            $id = $thumbnail->getId();
            foreach (\glob($this->absolutePath . $id . '.failed') as $f) {
                @\unlink($f);
            }
            $file = $this->absolutePath . $id;
            $ch = $this->curlInitDownload($thumbnail->getThumbnailUrl(), $file);
            if ($ch !== \false) {
                $handles[$embedUrl] = ['file' => $file, 'url' => $thumbnail->getThumbnailUrl(), 'ch' => $ch];
                \curl_multi_add_handle($mh, $ch);
            } else {
                $thumbnail->setError('We could not create a destination for this file, ignore it.');
            }
            // Download alternatives, too
            $alternativeThumbnailUrl = $thumbnail->getAlternativeThumbnailUrl();
            if (!empty($alternativeThumbnailUrl)) {
                $file .= '-alt';
                $ch = $this->curlInitDownload($alternativeThumbnailUrl, $file);
                if ($ch !== \false) {
                    $handles[$embedUrl . '-alt'] = ['file' => $file, 'url' => $alternativeThumbnailUrl, 'ch' => $ch];
                    \curl_multi_add_handle($mh, $ch);
                }
            }
        }
        $this->awaitCurlMultiExecution($mh, \array_column($handles, 'ch'));
        // All of our requests are done, we can now access the results
        foreach ($handles as $embedUrl => $handle) {
            $alternativeHandle = $handles[$embedUrl . '-alt'] ?? null;
            if (Utils::endsWith($embedUrl, '-alt')) {
                // Skip alternatives as they are treated in another way
                continue;
            }
            $thumbnail = $thumbnails[$embedUrl];
            if ($this->setBySingleResult($handle, $thumbnail)) {
                // The alternative thumbnail is not needed, delete it from filesystem
                if ($alternativeHandle) {
                    @\unlink($alternativeHandle['file']);
                }
                continue;
            }
            // Check if there is an alternative handle
            if ($alternativeHandle !== null && $this->setBySingleResult($alternativeHandle, $thumbnail)) {
                continue;
            }
            // Create `.failed` file so our cache can throw an error for failed downloads
            $thumbnail->setError('We could not create a destination for this file, ignore it.');
            \touch($this->absolutePath . $id . '.failed');
        }
    }
    /**
     * Single process a result within `$this::set()`.
     *
     * @param array $handle
     * @param Thumbnail $thumbnail
     */
    protected function setBySingleResult($handle, $thumbnail)
    {
        $ch = $handle['ch'];
        $file = $handle['file'];
        $url = $handle['url'];
        $status = \curl_getinfo($ch, \CURLINFO_HTTP_CODE);
        $id = $thumbnail->getId();
        if ($status === 200 && \is_file($file)) {
            $dimensions = \getimagesize($file);
            // The local copy cannot be used for image size calculation, use remote...
            if ($dimensions === \false) {
                $dimensions = \getimagesize($url);
            }
            if ($dimensions !== \false) {
                $width = $dimensions[0];
                $height = $dimensions[1];
                $mime = $dimensions['mime'];
                $extension = $this->extractImageExtension($mime);
                if ($extension !== \false) {
                    $md5File = \md5_file($file);
                    $newFilename = \sprintf('%s-%s.%s', $id, $md5File, $extension);
                    $newFile = \dirname($file) . '/' . $newFilename;
                    if (\rename($file, $newFile) && \filesize($newFile) < self::MAX_DOWNLOAD_SIZE) {
                        $thumbnail->setMd5File($md5File);
                        $thumbnail->setCacheUrl($this->prefixUrl . $newFilename);
                        $thumbnail->setWidth($width);
                        $thumbnail->setHeight($height);
                        // Write additional metadata info to own file so we can get it through our cache
                        \file_put_contents(\dirname($file) . \sprintf('/%s-%s.json', $id, $md5File), \json_encode(['width' => $width, 'height' => $height, 'title' => $thumbnail->getTitle(), 'forceRatio' => $thumbnail->getForceRatio()]));
                        return \true;
                    }
                }
            }
        }
        @\unlink($file);
        return \false;
    }
    /**
     * Check and parse the metadata file (ending with `.json`).
     *
     * @param string $filename The cached filename
     * @return array|null
     */
    protected function getCachedMetadata($filename)
    {
        $file = $this->absolutePath . \explode('.', $filename, 2)[0] . '.json';
        if (\is_readable($file)) {
            return \json_decode(\file_get_contents($file), ARRAY_A);
        }
        return null;
    }
    /**
     * Get absolute pathes to already existing files for a given hash (thumbnail ID).
     *
     * @param string $hash
     * @return string[]
     */
    protected function getCachedFilesForUrl($hash)
    {
        $res = \glob($this->absolutePath . $hash . '*');
        $res = $res === \false ? [] : \array_values(\array_filter($res, function ($f) {
            return !Utils::endsWith($f, '.json');
        }));
        \usort($res, function ($a, $b) {
            return \filemtime($a) <=> \filemtime($b);
        });
        return $res;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getPrefixUrl()
    {
        return $this->prefixUrl;
    }
}
