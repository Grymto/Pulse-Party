<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\DeliverAnonymousAsset;

use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Utils as UtilsUtils;
use WP_Filesystem_Direct;
use WP_Scripts;
/**
 * Deliver anonymous assets through `wp-content/uploads`.
 * @internal
 */
class DeliverAnonymousAsset
{
    /**
     * Builder.
     *
     * @var AnonymousAssetBuilder
     */
    private $builder;
    private $handle;
    private $file;
    /**
     * C'tor.
     *
     * @param AnonymousAssetBuilder $builder
     * @param string $handle
     * @param string $file
     * @codeCoverageIgnore
     */
    public function __construct($builder, $handle, $file)
    {
        $this->builder = $builder;
        $this->handle = $handle;
        $this->file = $file;
        $this->hooks();
    }
    /**
     * Create hooks.
     */
    protected function hooks()
    {
        \add_action('DevOwl/DeliverAnonymousAsset/Update/' . $this->getBuilder()->getOptionNamePrefix(), [$this, 'deleteOldHashes']);
        \add_filter('attribute_escape', [$this, 'attribute_escape']);
        \add_filter('script_loader_tag', [$this, 'script_loader_tag'], 10, 2);
    }
    /**
     * Delete all outdated files.
     *
     * @param string[] $deletedHashes
     * @deprecated This is only implemented for backwards compatibility to delete old files directly placed in `wp-content` instead of a subfolder
     */
    public function deleteOldHashes($deletedHashes)
    {
        $contentDir = $this->getBuilder()->getContentDir();
        // Instead of the old mechanism, we just read the directory for filenames matching MD5
        /*$extension = pathinfo($this->getFile(), PATHINFO_EXTENSION);
                foreach ($deletedHashes as $deletedHash) {
                    $filename = md5($deletedHash . $this->getHandle()) . '.' . $extension;
                    $filename = $contentDir . $filename;
        
                    if (file_exists($filename)) {
                        unlink($filename);
                    }
                }*/
        // @codeCoverageIgnoreStart
        if (!\defined('PHPUNIT_FILE')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        // @codeCoverageIgnoreEnd
        foreach (\list_files($contentDir, 1) as $file) {
            if (\strlen(\basename($file)) === 35 && \is_readable($file) && \time() - \filemtime($file) > 28 * 24 * 60 * 60) {
                $fileContent = \file_get_contents($file);
                if (\strpos($fileContent, 'realCookieBanner') !== \false || \strpos($fileContent, '__tcfapiLocator') !== \false) {
                    \unlink($file);
                }
            }
        }
    }
    /**
     * The handle is enqueued, let's modify the `WP_Dependency`.
     */
    public function ready()
    {
        $builder = $this->getBuilder();
        // Check if folder can be created and is writable
        if (!$builder->ensureAnonymousFolder()) {
            return \false;
        }
        $script = \wp_scripts()->query($this->getHandle());
        if (!$script) {
            return \false;
        }
        // Check if already adjusted
        $usedFilenameWithoutExtension = \explode('.', \basename($script->src))[0];
        if (\strlen($usedFilenameWithoutExtension) !== 32) {
            $script->src = $this->generateSrc();
            return \true;
        }
        return \false;
    }
    /**
     * Generate the file in our content directory and return the URL.
     */
    protected function generateSrc()
    {
        $anonymousFolder = $this->getBuilder()->ensureAnonymousFolder(\true);
        $contentDir = \wp_normalize_path(\constant('WP_CONTENT_DIR') . '/');
        $contentPath = $anonymousFolder . AnonymousAssetBuilder::generateFilename($this->getBuilder()->getHash(), $this->getFile());
        $contentUrl = Utils::getContentUrl();
        if (!\file_exists($contentPath)) {
            // The file does not exist, perhaps it was not part of the passed `$folder` in `AnnonymousAssetBulder` constructor?
            // This could happen for e.g. libraries in `public/lib/`
            \file_put_contents($contentPath, Utils::readFileAndCorrectSourceMap($this->getFile()));
            UtilsUtils::runDirectFilesystem(function ($fs) use($contentPath) {
                /**
                 * WP_Filesystem_Direct.
                 *
                 *  @var WP_Filesystem_Direct
                 */
                $fs = $fs;
                $fs->chmod($contentPath, \constant('FS_CHMOD_FILE'));
            });
            // ...or switching from free version to PRO version
            $this->getBuilder()->ensureAnonymousFolder(\false);
        }
        return $contentUrl . \substr($contentPath, \strlen($contentDir));
    }
    /**
     * Modify CData script tag.
     *
     * @param string $safe_text The text after it has been escaped.
     */
    public function attribute_escape($safe_text)
    {
        if ($safe_text === $this->getHandle()) {
            // Backtrace to detect only changes in `print_extra_script`
            // phpcs:disable
            $backtrace = @\debug_backtrace();
            // phpcs:enable
            foreach ($backtrace as $bt) {
                if (isset($bt['function'], $bt['class']) && $bt['function'] === 'print_extra_script' && $bt['class'] === WP_Scripts::class) {
                    return \md5(\rand());
                }
            }
        }
        return $safe_text;
    }
    /**
     * Modify tags to now show any `id` attribute.
     *
     * @param string $tag The `<script>` tag for the enqueued script.
     * @param string $handle The script's registered handle.
     */
    public function script_loader_tag($tag, $handle)
    {
        if ($handle === $this->getHandle()) {
            return \str_replace("id='" . $this->getHandle() . "-js'", '', $tag);
        }
        return $tag;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getBuilder()
    {
        return $this->builder;
    }
    /**
     * Get handle.
     *
     * @codeCoverageIgnore
     */
    public function getHandle()
    {
        return $this->handle;
    }
    /**
     * Get file.
     *
     * @codeCoverageIgnore
     */
    public function getFile()
    {
        return $this->file;
    }
}
