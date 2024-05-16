<?php

namespace DevOwl\RealCookieBanner\scanner;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\Utils;
use WP_Post;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Automatically detect changes to pages and posts, or CPT's and scan them again.
 * @internal
 */
class OnChangeDetection
{
    use UtilsProvider;
    /**
     * In some cases, custom post types are used in "Draft"-always state or private, so let's
     * use the preview URL (`?preview=true`) instead of the permalink URL.
     */
    const USE_PREVIEW_LINK_FOR_POST_TYPE = [
        // [Plugin Comp] https://wordpress.org/plugins/coming-soon/
        'seedprod',
    ];
    /**
     * In some cases it does not make sense to scan viewable post types as they act e.g. as "template"
     * posts which are reused in publicly available posts and pages.
     *
     * E.g. `elementor_library` still needs to be scanned as the post type is resused in Elementor pages and
     * they do not get automatically scanned. In a perfect world, when `elementor_library` got changed, it needs
     * to add all URLs of the posts which uses it.
     */
    const SKIP_POST_TYPE = [];
    private $scanner;
    /**
     * C'tor.
     *
     * @param Scanner $scanner
     * @codeCoverageIgnore
     */
    public function __construct($scanner)
    {
        $this->scanner = $scanner;
    }
    /**
     * A post got updated or created, add it to our queue.
     *
     * @param int $post_id
     * @param WP_Post $post
     */
    public function save_post($post_id, $post)
    {
        $this->fromPost($post);
    }
    /**
     * The `post_updated` hook is fired before `save_post` so we can identify if the slug has been changed (parent or slug).
     *
     * @param int $post_id
     * @param WP_Post $post_after
     * @param WP_Post $post_before
     */
    public function post_updated($post_id, $post_after, $post_before)
    {
        $permalinkAfter = $this->getPermalink($post_after);
        $permalinkBefore = $this->getPermalink($post_before);
        if ($permalinkAfter !== $permalinkBefore) {
            $this->scanner->getQuery()->removeSourceUrls([$permalinkBefore]);
        }
    }
    /**
     * A post got deleted. Remove the URL from the scan results.
     *
     * @param int $post_id
     */
    public function delete_post($post_id)
    {
        $post = \get_post($post_id);
        if ($post) {
            $link = $this->getPermalink($post);
            if (!empty($link)) {
                $this->scanner->getQuery()->removeSourceUrls([$link]);
            }
        }
    }
    /**
     * A post got moved to the trash. Remove the URL from the scan results.
     *
     * @param int $post_id
     */
    public function wp_trash_post($post_id)
    {
        $link = $this->getPermalink(\get_post($post_id));
        if (!empty($link)) {
            $this->scanner->getQuery()->removeSourceUrls([$link]);
        }
    }
    /**
     * Check if the post can be queried publicly and add it to our queue.
     *
     * @param WP_Post $post
     */
    protected function fromPost($post)
    {
        if (\is_post_type_viewable($post->post_type)) {
            $link = $this->getPermalink($post);
            if (!empty($link)) {
                if (($post->post_status === 'publish' || \in_array($post->post_type, self::USE_PREVIEW_LINK_FOR_POST_TYPE, \true)) && !\in_array($post->post_type, self::SKIP_POST_TYPE, \true)) {
                    $this->addUrlToScanner($link);
                } elseif ($post->post_status !== 'auto-draft') {
                    // Handle e.g. "Draft" like a deletion
                    $this->scanner->getQuery()->removeSourceUrls([$link]);
                }
            }
        }
    }
    /**
     * Get permalink to a given post which can be inserted to the scanner.
     *
     * @param WP_Post $post
     */
    protected function getPermalink($post)
    {
        return \in_array($post->post_type, self::USE_PREVIEW_LINK_FOR_POST_TYPE, \true) ? Utils::getPreviewUrl($post) : Utils::getPermalink($post);
    }
    /**
     * Add a changed permalink URL to the scanner queue. It also respects multilingual
     * websites and adds all language translations to the scanner queue (e.g. `/de/my-post`
     * and `/en/my-post`).
     *
     * @param string $url
     */
    protected function addUrlToScanner($url)
    {
        $urls = [$url];
        $compLanguage = Core::getInstance()->getCompLanguage();
        if ($compLanguage !== null) {
            foreach ($compLanguage->getActiveLanguages() as $locale) {
                $translatedUrl = $compLanguage->getPermalink($url, $locale);
                if (!empty($translatedUrl)) {
                    $urls[] = $translatedUrl;
                }
            }
        }
        $this->scanner->addUrlsToQueue(\array_unique($urls));
    }
}
