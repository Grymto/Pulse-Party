<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\imagePreview;

use CurlHandle;
use CurlMultiHandle;
/**
 * Provide an interface and mechanism to cache extracted image previews in a folder/database
 * and generate a local URL.
 * @internal
 */
abstract class ImagePreviewCache
{
    /**
     * Take an URL which needs to be checked if it has cached a thumbnail and set data accordingly:
     *
     * - MD5 File checksum
     * - Cache URL
     * - Title
     * - Thumbnail width
     * - Thumbnail height
     *
     * If there was an error with the file downloading previously, `setError` for the `Thumbnail`.
     *
     * @param Thumbnail[] $thumbnails Key is the embed URL
     */
    public abstract function get($thumbnails);
    /**
     * We found a thumbnail and additional data for a fetched URL. Let's cache it and set data accordingly: see `get`.
     *
     * @param Thumbnail[] $thumbnails Key is the embed URL
     */
    public abstract function set($thumbnails);
    /**
     * Our image preview processor has found a thumbnail URL for a given website URL. Let's check if using this thumbnail
     * URL is allowed by using `setAllowance`. Why? This could be a license/copyright violation and needs to be consent by
     * the user via a custom UI.
     *
     * @param Thumbnail[] $thumbnails Key is the embed URL
     */
    public abstract function allowance($thumbnails);
    /**
     * Create a curl handle which you could execute or add to a multi-curl instance which downloads a file
     * to a given target on your filesystem.
     *
     * @param string $url
     * @param string $target
     */
    public function curlInitDownload($url, $target)
    {
        $ch = \curl_init();
        $fopen = @\fopen($target, 'w');
        if ($fopen !== \false) {
            \curl_setopt_array($ch, [\CURLOPT_FILE => $fopen, \CURLOPT_TIMEOUT => 5, \CURLOPT_URL => $url]);
            return $ch;
        } else {
            return \false;
        }
    }
    /**
     * Execute all queries simultaneously, and continue when all are complete (+ closes the handles).
     *
     * @param resource|CurlMultiHandle $mh
     * @param CurlHandle[] $chs
     */
    public function awaitCurlMultiExecution($mh, $chs)
    {
        do {
            $status = \curl_multi_exec($mh, $active);
            if ($active) {
                \curl_multi_select($mh);
            }
        } while ($active && $status === \CURLM_OK);
        // Close the handles
        foreach ($chs as $ch) {
            \curl_multi_remove_handle($mh, $ch);
        }
        \curl_multi_close($mh);
    }
    /**
     * Extract an extension for a given image mime-type.
     *
     * @param string $mime
     */
    public function extractImageExtension($mime)
    {
        $all_mimes = ['png' => ['image/png', 'image/x-png'], 'bmp' => ['image/bmp', 'image/x-bmp', 'image/x-bitmap', 'image/x-xbitmap', 'image/x-win-bitmap', 'image/x-windows-bmp', 'image/ms-bmp', 'image/x-ms-bmp', 'application/bmp', 'application/x-bmp', 'application/x-win-bitmap'], 'gif' => ['image/gif'], 'jpeg' => ['image/jpeg', 'image/pjpeg'], 'svg' => ['image/svg+xml'], 'jp2' => ['image/jp2', 'video/mj2', 'image/jpx', 'image/jpm'], 'tiff' => ['image/tiff'], 'ico' => ['image/x-icon', 'image/x-ico', 'image/vnd.microsoft.icon']];
        foreach ($all_mimes as $key => $value) {
            if (\array_search($mime, $value, \true) !== \false) {
                return $key;
            }
        }
        return \false;
    }
}
