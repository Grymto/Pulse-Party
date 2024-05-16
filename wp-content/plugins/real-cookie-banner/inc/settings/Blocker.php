<?php

namespace DevOwl\RealCookieBanner\settings;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\services\Blocker as ServicesBlocker;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Cache;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\lite\settings\Blocker as LiteBlocker;
use DevOwl\RealCookieBanner\lite\settings\TcfVendorConfiguration;
use DevOwl\RealCookieBanner\Localization;
use DevOwl\RealCookieBanner\overrides\interfce\settings\IOverrideBlocker;
use DevOwl\RealCookieBanner\view\Checklist;
use DevOwl\RealCookieBanner\view\checklist\AddBlocker;
use WP_Error;
use WP_Post;
use WP_REST_Posts_Controller;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Register content blocker custom post type.
 * @internal
 */
class Blocker implements IOverrideBlocker
{
    use UtilsProvider;
    use LiteBlocker;
    const CPT_NAME = 'rcb-blocker';
    const META_NAME_PRESET_ID = 'presetId';
    const META_NAME_PRESET_VERSION = 'presetVersion';
    const META_NAME_RULES = 'rules';
    const META_NAME_CRITERIA = 'criteria';
    const META_NAME_TCF_VENDORS = 'tcfVendors';
    const META_NAME_TCF_PURPOSES = 'tcfPurposes';
    const META_NAME_SERVICES = 'services';
    const META_NAME_IS_VISUAL = 'isVisual';
    const META_NAME_VISUAL_TYPE = 'visualType';
    const META_NAME_VISUAL_MEDIA_THUMBNAIL = 'visualMediaThumbnail';
    const META_NAME_VISUAL_CONTENT_TYPE = 'visualContentType';
    const META_NAME_IS_VISUAL_DARK_MODE = 'isVisualDarkMode';
    const META_NAME_VISUAL_BLUR = 'visualBlur';
    const META_NAME_VISUAL_DOWNLOAD_THUMBNAIL = 'visualDownloadThumbnail';
    const META_NAME_VISUAL_HERO_BUTTON_TEXT = 'visualHeroButtonText';
    const META_NAME_SHOULD_FORCE_TO_SHOW_VISUAL = 'shouldForceToShowVisual';
    const SYNC_OPTIONS_COPY = [\DevOwl\RealCookieBanner\settings\Blocker::META_NAME_RULES, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_CRITERIA, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_TCF_VENDORS, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_TCF_PURPOSES, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_SERVICES, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_IS_VISUAL, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_VISUAL_TYPE, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_VISUAL_MEDIA_THUMBNAIL, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_VISUAL_CONTENT_TYPE, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_IS_VISUAL_DARK_MODE, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_VISUAL_BLUR, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_VISUAL_DOWNLOAD_THUMBNAIL, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_SHOULD_FORCE_TO_SHOW_VISUAL, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_PRESET_ID, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_PRESET_VERSION];
    const SYNC_OPTIONS_COPY_ONCE = [\DevOwl\RealCookieBanner\settings\Blocker::META_NAME_VISUAL_HERO_BUTTON_TEXT];
    const SYNC_OPTIONS = ['meta' => ['copy' => \DevOwl\RealCookieBanner\settings\Blocker::SYNC_OPTIONS_COPY, 'copy-once' => \DevOwl\RealCookieBanner\settings\Blocker::SYNC_OPTIONS_COPY_ONCE]];
    const META_KEYS = [\DevOwl\RealCookieBanner\settings\Blocker::META_NAME_RULES, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_CRITERIA, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_TCF_VENDORS, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_TCF_PURPOSES, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_SERVICES, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_IS_VISUAL, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_VISUAL_TYPE, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_VISUAL_MEDIA_THUMBNAIL, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_VISUAL_CONTENT_TYPE, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_IS_VISUAL_DARK_MODE, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_VISUAL_BLUR, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_VISUAL_DOWNLOAD_THUMBNAIL, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_VISUAL_HERO_BUTTON_TEXT, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_SHOULD_FORCE_TO_SHOW_VISUAL, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_PRESET_ID];
    /**
     * Singleton instance.
     *
     * @var Blocker
     */
    private static $me = null;
    private $cacheGetOrdered = [];
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Register custom post type.
     */
    public function register()
    {
        $labels = ['name' => \__('Content Blockers', RCB_TD), 'singular_name' => \__('Content Blocker', RCB_TD)];
        $args = ['label' => $labels['name'], 'labels' => $labels, 'description' => '', 'public' => \false, 'publicly_queryable' => \false, 'show_ui' => \true, 'show_in_rest' => \true, 'rest_base' => self::CPT_NAME, 'rest_controller_class' => WP_REST_Posts_Controller::class, 'has_archive' => \false, 'show_in_menu' => \false, 'show_in_nav_menus' => \false, 'delete_with_user' => \false, 'exclude_from_search' => \true, 'capabilities' => \DevOwl\RealCookieBanner\settings\Cookie::CAPABILITIES, 'map_meta_cap' => \false, 'hierarchical' => \false, 'rewrite' => \false, 'query_var' => \true, 'supports' => ['title', 'editor', 'custom-fields']];
        \register_post_type(self::CPT_NAME, $args);
        \register_meta('post', self::META_NAME_PRESET_ID, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_PRESET_VERSION, ['object_subtype' => self::CPT_NAME, 'type' => 'number', 'single' => \true, 'show_in_rest' => \true]);
        // This meta is stored as line-separated list (this shouldn't be done usually - 3rd normal form - but it's ok here)
        \register_meta('post', self::META_NAME_RULES, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_CRITERIA, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true, 'default' => ServicesBlocker::DEFAULT_CRITERIA]);
        // This meta is stored as JSON (this shouldn't be done usually - 3rd normal form - but it's ok here)
        \register_meta('post', self::META_NAME_TCF_VENDORS, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        // This meta is stored as JSON (this shouldn't be done usually - 3rd normal form - but it's ok here)
        \register_meta('post', self::META_NAME_TCF_PURPOSES, [
            'object_subtype' => self::CPT_NAME,
            'type' => 'string',
            'single' => \true,
            'show_in_rest' => \true,
            // Always add "Store and/or access information on a device" as this is the most-friendly option
            // to the website operator, which covers not to load the embedded content without consent
            'default' => '1',
        ]);
        // This meta is stored as JSON (this shouldn't be done usually - 3rd normal form - but it's ok here)
        \register_meta('post', self::META_NAME_SERVICES, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_IS_VISUAL, ['object_subtype' => self::CPT_NAME, 'type' => 'boolean', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_VISUAL_TYPE, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true, 'default' => ServicesBlocker::DEFAULT_VISUAL_TYPE]);
        \register_meta('post', self::META_NAME_VISUAL_MEDIA_THUMBNAIL, ['object_subtype' => self::CPT_NAME, 'type' => 'number', 'single' => \true, 'show_in_rest' => \true, 'default' => 0]);
        \register_meta('post', self::META_NAME_VISUAL_CONTENT_TYPE, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true, 'default' => '']);
        \register_meta('post', self::META_NAME_IS_VISUAL_DARK_MODE, ['object_subtype' => self::CPT_NAME, 'type' => 'boolean', 'single' => \true, 'show_in_rest' => \true, 'default' => \false]);
        \register_meta('post', self::META_NAME_VISUAL_BLUR, ['object_subtype' => self::CPT_NAME, 'type' => 'number', 'single' => \true, 'show_in_rest' => \true, 'default' => 0]);
        \register_meta('post', self::META_NAME_VISUAL_DOWNLOAD_THUMBNAIL, ['object_subtype' => self::CPT_NAME, 'type' => 'boolean', 'single' => \true, 'show_in_rest' => \true, 'default' => \false]);
        \register_meta('post', self::META_NAME_VISUAL_HERO_BUTTON_TEXT, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true, 'default' => '']);
        \register_meta('post', self::META_NAME_SHOULD_FORCE_TO_SHOW_VISUAL, ['object_subtype' => self::CPT_NAME, 'type' => 'boolean', 'single' => \true, 'show_in_rest' => \true]);
    }
    /**
     * A blocker was saved.
     *
     * @param int $post_ID
     * @param WP_Post $post
     * @param boolean $update
     */
    public function save_post($post_ID, $post, $update)
    {
        if (!$update) {
            Checklist::getInstance()->toggle(AddBlocker::IDENTIFIER, \true);
        }
        // Keep "Already created" in service templates intact
        if (!$update) {
            \wp_rcb_invalidate_templates_cache(\true);
        }
        Core::getInstance()->getNotices()->recalculatePostDependingNotices();
    }
    /**
     * A cookie got deleted, also delete all associations from content blocker.
     *
     * @param int $postId
     */
    public function deleted_post($postId)
    {
        $post_type = \get_post_type($postId);
        $sync = Core::getInstance()->getCompLanguage()->getSync();
        if ($post_type === \DevOwl\RealCookieBanner\settings\Cookie::CPT_NAME || $this->isPro() && $post_type === TcfVendorConfiguration::CPT_NAME) {
            $blockers = $this->getOrdered(\false, \get_posts(Core::getInstance()->queryArguments(['post_type' => \DevOwl\RealCookieBanner\settings\Blocker::CPT_NAME, 'orderby' => ['ID' => 'DESC'], 'numberposts' => -1, 'nopaging' => \true, 'post_status' => ['publish', 'private', 'draft']], 'blockerDeleteCookies')));
            foreach ($blockers as $blocker) {
                $metaKey = $post_type === \DevOwl\RealCookieBanner\settings\Cookie::CPT_NAME ? \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_SERVICES : \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_TCF_VENDORS;
                $cookies = $blocker->metas[$metaKey];
                if (($key = \array_search($postId, $cookies, \true)) !== \false) {
                    unset($cookies[$key]);
                    if ($sync !== null) {
                        $sync->disable();
                    }
                    \update_post_meta($blocker->ID, $metaKey, \join(',', $cookies));
                    if ($sync !== null) {
                        $sync->enable();
                    }
                }
            }
        }
        // Cleanup transients so templates get regenerated
        if (\in_array($post_type, [\DevOwl\RealCookieBanner\settings\Blocker::CPT_NAME, \DevOwl\RealCookieBanner\settings\Cookie::CPT_NAME], \true)) {
            \wp_rcb_invalidate_templates_cache(\true);
        }
        // Clear cache for blockers
        if ($post_type === \DevOwl\RealCookieBanner\settings\Blocker::CPT_NAME) {
            Cache::getInstance()->invalidate();
        }
    }
    /**
     * Get all available content blocker ordered.
     *
     * @param boolean $force
     * @param WP_Post[] $usePosts If set, only meta is applied to the passed posts
     * @return WP_Post[]|WP_Error
     */
    public function getOrdered($force = \false, $usePosts = null)
    {
        $context = \DevOwl\RealCookieBanner\settings\Revision::getInstance()->getContextVariablesString();
        if ($force === \false && isset($this->cacheGetOrdered[$context]) && $usePosts === null) {
            return $this->cacheGetOrdered[$context];
        }
        $posts = $usePosts === null ? \get_posts(Core::getInstance()->queryArguments(['post_type' => self::CPT_NAME, 'orderby' => ['ID' => 'DESC'], 'numberposts' => -1, 'nopaging' => \true, 'post_status' => 'publish'], 'blockerGetOrdered')) : $usePosts;
        foreach ($posts as &$post) {
            $post->metas = [];
            foreach (self::META_KEYS as $meta_key) {
                $metaValue = \get_post_meta($post->ID, $meta_key, \true);
                switch ($meta_key) {
                    case self::META_NAME_RULES:
                        $metaValue = \explode("\n", $metaValue);
                        $metaValue = \array_values(\array_filter(\array_map('trim', $metaValue), 'strlen'));
                        break;
                    case self::META_NAME_TCF_VENDORS:
                    case self::META_NAME_TCF_PURPOSES:
                    case self::META_NAME_SERVICES:
                        $metaValue = empty($metaValue) ? [] : \array_map('intval', \explode(',', $metaValue));
                        break;
                    case self::META_NAME_IS_VISUAL:
                    case self::META_NAME_SHOULD_FORCE_TO_SHOW_VISUAL:
                        $metaValue = \boolval($metaValue);
                        break;
                    default:
                        break;
                }
                $post->metas[$meta_key] = $metaValue;
            }
            $this->overrideGetOrderedCastMeta($post, $post->metas);
        }
        if ($usePosts === null) {
            $this->cacheGetOrdered[$context] = $posts;
        }
        return $posts;
    }
    /**
     * Localize available content blockers for frontend.
     */
    public function toJson()
    {
        $output = [];
        $blockers = $this->getOrdered();
        foreach ($blockers as $blocker) {
            $output[] = \array_merge(['id' => $blocker->ID, 'name' => $blocker->post_title, 'description' => $blocker->post_content], $blocker->metas);
        }
        return Core::getInstance()->getCompLanguage()->translateArray($output, \array_merge(self::SYNC_OPTIONS_COPY, Localization::COMMON_SKIP_KEYS), null, ['legal-text']);
    }
    /**
     * Get a total count of all blockers.
     *
     * @return int
     */
    public function getAllCount()
    {
        return \array_sum(\array_map('intval', \array_values((array) \wp_count_posts(self::CPT_NAME))));
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\settings\Blocker() : self::$me;
    }
}
