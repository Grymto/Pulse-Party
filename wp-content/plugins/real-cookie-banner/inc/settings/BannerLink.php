<?php

namespace DevOwl\RealCookieBanner\settings;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\BannerLink as SettingsBannerLink;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual\None;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\Localization;
use DevOwl\RealCookieBanner\Utils;
use DevOwl\RealCookieBanner\view\checklist\PrivacyPolicy;
use DevOwl\RealCookieBanner\view\checklist\PrivacyPolicyMentionUsage;
use WP_Error;
use WP_Post;
use WP_REST_Posts_Controller;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Register banner link (currently only footer links) custom post type.
 * @internal
 */
class BannerLink
{
    use UtilsProvider;
    const CPT_NAME = 'rcb-banner-link';
    const META_NAME_PAGE_TYPE = 'pageType';
    const META_NAME_IS_EXTERNAL_URL = 'isExternalUrl';
    const META_NAME_PAGE_ID = 'pageId';
    const META_NAME_EXTERNAL_URL = 'externalUrl';
    const META_NAME_HIDE_COOKIE_BANNER = 'hideCookieBanner';
    /**
     * Should the link be opened in a new window?
     *
     * @deprecated Just used for backwards-compatibility
     */
    const META_NAME_IS_TARGET_BLANK = 'isTargetBlank';
    const SYNC_META_COPY = [\DevOwl\RealCookieBanner\settings\BannerLink::META_NAME_PAGE_TYPE, \DevOwl\RealCookieBanner\settings\BannerLink::META_NAME_IS_EXTERNAL_URL, \DevOwl\RealCookieBanner\settings\BannerLink::META_NAME_PAGE_ID, \DevOwl\RealCookieBanner\settings\BannerLink::META_NAME_HIDE_COOKIE_BANNER, \DevOwl\RealCookieBanner\settings\BannerLink::META_NAME_IS_TARGET_BLANK];
    const SYNC_META_COPY_ONCE = [\DevOwl\RealCookieBanner\settings\BannerLink::META_NAME_EXTERNAL_URL];
    const SYNC_OPTIONS = ['data' => ['menu_order'], 'meta' => ['copy' => \DevOwl\RealCookieBanner\settings\BannerLink::SYNC_META_COPY, 'copy-once' => \DevOwl\RealCookieBanner\settings\BannerLink::SYNC_META_COPY_ONCE]];
    const META_KEYS = [\DevOwl\RealCookieBanner\settings\BannerLink::META_NAME_PAGE_TYPE, \DevOwl\RealCookieBanner\settings\BannerLink::META_NAME_IS_EXTERNAL_URL, \DevOwl\RealCookieBanner\settings\BannerLink::META_NAME_PAGE_ID, \DevOwl\RealCookieBanner\settings\BannerLink::META_NAME_EXTERNAL_URL, \DevOwl\RealCookieBanner\settings\BannerLink::META_NAME_HIDE_COOKIE_BANNER, \DevOwl\RealCookieBanner\settings\BannerLink::META_NAME_IS_TARGET_BLANK];
    /**
     * Singleton instance.
     *
     * @var BannerLink
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
        // Keep this array for translation purposes, so it can be used with `@devowl-wp/multilingual` and auto translate
        [SettingsBannerLink::PAGE_TYPE_GENERAL_TERMS_AND_CONDITIONS => \__('General terms and conditions', RCB_TD), SettingsBannerLink::PAGE_TYPE_TERMS_OF_USE => \__('Terms of use', RCB_TD), SettingsBannerLink::PAGE_TYPE_CANCELLATION_POLICY => \__('Cancellation policy', RCB_TD), SettingsBannerLink::PAGE_TYPE_COOKIE_POLICY => \__('Cookie policy', RCB_TD), SettingsBannerLink::PAGE_TYPE_DATA_PROCESSING_AGREEMENT => \__('Data processing agreement', RCB_TD), SettingsBannerLink::PAGE_TYPE_DISPUTE_RESOLUTION => \__('Dispute resolution', RCB_TD)];
        $labels = ['name' => \__('Banner links', RCB_TD), 'singular_name' => \__('Banner link', RCB_TD)];
        $args = ['label' => $labels['name'], 'labels' => $labels, 'description' => '', 'public' => \false, 'publicly_queryable' => \false, 'show_ui' => \true, 'show_in_rest' => \true, 'rest_base' => self::CPT_NAME, 'rest_controller_class' => WP_REST_Posts_Controller::class, 'has_archive' => \false, 'show_in_menu' => \false, 'show_in_nav_menus' => \false, 'delete_with_user' => \false, 'exclude_from_search' => \true, 'capabilities' => \DevOwl\RealCookieBanner\settings\Cookie::CAPABILITIES, 'map_meta_cap' => \false, 'hierarchical' => \false, 'rewrite' => \false, 'query_var' => \true, 'supports' => ['title', 'editor', 'custom-fields', 'page-attributes']];
        \register_post_type(self::CPT_NAME, $args);
        \register_meta('post', self::META_NAME_PAGE_TYPE, ['object_subtype' => self::CPT_NAME, 'type' => 'boolean', 'single' => \true, 'show_in_rest' => ['schema' => ['type' => 'string', 'enum' => [SettingsBannerLink::PAGE_TYPE_LEGAL_NOTICE, SettingsBannerLink::PAGE_TYPE_PRIVACY_POLICY, SettingsBannerLink::PAGE_TYPE_GENERAL_TERMS_AND_CONDITIONS, SettingsBannerLink::PAGE_TYPE_TERMS_OF_USE, SettingsBannerLink::PAGE_TYPE_CANCELLATION_POLICY, SettingsBannerLink::PAGE_TYPE_COOKIE_POLICY, SettingsBannerLink::PAGE_TYPE_DATA_PROCESSING_AGREEMENT, SettingsBannerLink::PAGE_TYPE_DISPUTE_RESOLUTION, SettingsBannerLink::PAGE_TYPE_OTHER]]]]);
        \register_meta('post', self::META_NAME_IS_EXTERNAL_URL, ['object_subtype' => self::CPT_NAME, 'type' => 'boolean', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_PAGE_ID, ['object_subtype' => self::CPT_NAME, 'type' => 'number', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_EXTERNAL_URL, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_HIDE_COOKIE_BANNER, ['object_subtype' => self::CPT_NAME, 'type' => 'boolean', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_IS_TARGET_BLANK, ['object_subtype' => self::CPT_NAME, 'type' => 'boolean', 'single' => \true, 'show_in_rest' => \true]);
    }
    /**
     * Get all available banner links ordered.
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
        $posts = $usePosts === null ? \get_posts(Core::getInstance()->queryArguments(['post_type' => self::CPT_NAME, 'orderby' => ['menu_order' => 'ASC', 'ID' => 'DESC'], 'numberposts' => -1, 'nopaging' => \true, 'post_status' => 'publish'], 'bannerLinkGetOrdered')) : $usePosts;
        foreach ($posts as &$post) {
            $post->metas = [];
            foreach (self::META_KEYS as $meta_key) {
                $metaValue = \get_post_meta($post->ID, $meta_key, \true);
                switch ($meta_key) {
                    case self::META_NAME_PAGE_ID:
                        $metaValue = \intval($metaValue);
                        break;
                    case self::META_NAME_IS_EXTERNAL_URL:
                    case self::META_NAME_HIDE_COOKIE_BANNER:
                    case self::META_NAME_IS_TARGET_BLANK:
                        $metaValue = \boolval($metaValue);
                        break;
                    default:
                        break;
                }
                $post->metas[$meta_key] = $metaValue;
            }
        }
        if ($usePosts === null) {
            $this->cacheGetOrdered[$context] = $posts;
        }
        return $posts;
    }
    /**
     * Get legal link (legal notice or privacy policy) as URL or page ID.
     *
     * @param string $pageType
     * @param string $returnType Can be `url` or `id`
     */
    public function getLegalLink($pageType, $returnType)
    {
        $compLanguage = Core::getInstance()->getCompLanguage();
        foreach ($this->getOrdered() as $bannerLink) {
            $bPageType = $bannerLink->metas[self::META_NAME_PAGE_TYPE] ?? '';
            if ($bPageType === $pageType) {
                $isExternalUrl = $bannerLink->metas[self::META_NAME_IS_EXTERNAL_URL] ?? \false;
                $externalUrl = $bannerLink->metas[self::META_NAME_EXTERNAL_URL] ?? \false;
                $pageId = $bannerLink->metas[self::META_NAME_PAGE_ID] ?? \false;
                $url = '';
                if ($isExternalUrl) {
                    $url = $externalUrl;
                } elseif ($pageId > 0) {
                    $pageId = $compLanguage->getCurrentPostId($pageId, 'page');
                    if ($returnType === 'id') {
                        return $pageId;
                    } else {
                        $permalink = Utils::getPermalink($pageId);
                        if ($permalink !== \false) {
                            $url = $permalink;
                        }
                    }
                }
                if ($returnType === 'url' && !empty($url)) {
                    return $url;
                }
            }
        }
        return $returnType === 'url' ? '' : 0;
    }
    /**
     * Localize available banner links for frontend.
     */
    public function toJson()
    {
        $output = [];
        $bannerLinks = $this->getOrdered();
        $compLanguage = Core::getInstance()->getCompLanguage();
        foreach ($bannerLinks as $bannerLink) {
            $url = '';
            $pageId = $bannerLink->metas[self::META_NAME_PAGE_ID];
            if ($bannerLink->metas[self::META_NAME_IS_EXTERNAL_URL]) {
                $url = $bannerLink->metas[self::META_NAME_EXTERNAL_URL];
            } elseif ($pageId > 0) {
                $pageId = $compLanguage->getCurrentPostId($pageId, 'page');
                $permalink = Utils::getPermalink($pageId);
                if ($permalink !== \false) {
                    $url = $permalink;
                }
            }
            if (!empty($url)) {
                $output[] = ['id' => $bannerLink->ID, 'label' => $bannerLink->post_title, 'pageType' => $bannerLink->metas[self::META_NAME_PAGE_TYPE], 'url' => $url, 'hideCookieBanner' => $bannerLink->metas[self::META_NAME_HIDE_COOKIE_BANNER], 'isTargetBlank' => $bannerLink->metas[self::META_NAME_IS_TARGET_BLANK]];
            }
        }
        return Core::getInstance()->getCompLanguage()->translateArray($output, Localization::COMMON_SKIP_KEYS, null, ['legal-text']);
    }
    /**
     * Create default banner links (legal notice, privacy policy) from options known
     * from older Real Cookie Banner versions or just blank links, so the user does not need
     * to click on "Add another link" in the Settings UI.
     *
     * @param boolean $backwardsCompatibility If `true`, it will try to restore the links from previous installed version
     */
    public function createDefaults($backwardsCompatibility = \false)
    {
        $fn = function () use($backwardsCompatibility) {
            global $wpdb;
            $compLanguage = Core::getInstance()->getCompLanguage();
            $activeLangauges = $compLanguage->getActiveLanguages();
            $td = Hooks::getInstance()->createTemporaryTextDomain();
            if (\count($this->getOrdered(\true)) > 0) {
                // There are already links created, skip creation
                return;
            }
            $entries = ['privacyPolicy' => ['post_type' => self::CPT_NAME, 'post_title' => \_x('Privacy policy', 'legal-text', Hooks::TD_FORCED), 'post_content' => '', 'menu_order' => 0, 'meta_input' => [self::META_NAME_PAGE_TYPE => SettingsBannerLink::PAGE_TYPE_PRIVACY_POLICY, self::META_NAME_IS_EXTERNAL_URL => \false, self::META_NAME_PAGE_ID => \DevOwl\RealCookieBanner\settings\General::getInstance()->getDefaultPrivacyPolicy(), self::META_NAME_EXTERNAL_URL => '', self::META_NAME_HIDE_COOKIE_BANNER => \true, self::META_NAME_IS_TARGET_BLANK => \true], 'post_status' => 'publish']];
            $backwardsCompatibilityFilters = [];
            // Add legal notice only when the blog language is from DACH
            $wpLanguages = $compLanguage instanceof None ? [\get_locale()] : \array_map([$compLanguage, 'getWordPressCompatibleLanguageCode'], $activeLangauges);
            $dachLanguages = \array_filter($wpLanguages, function ($l) {
                return Utils::startsWith($l, 'de');
            });
            if ($backwardsCompatibility || \count($dachLanguages) > 0) {
                $entries['legalNotice'] = ['post_type' => self::CPT_NAME, 'post_title' => \_x('Legal notice', 'legal-text', Hooks::TD_FORCED), 'post_content' => '', 'menu_order' => 1, 'meta_input' => [self::META_NAME_PAGE_TYPE => SettingsBannerLink::PAGE_TYPE_LEGAL_NOTICE, self::META_NAME_IS_EXTERNAL_URL => \false, self::META_NAME_PAGE_ID => 0, self::META_NAME_EXTERNAL_URL => '', self::META_NAME_HIDE_COOKIE_BANNER => \true, self::META_NAME_IS_TARGET_BLANK => \true], 'post_status' => 'publish'];
            }
            if ($backwardsCompatibility) {
                // Load from `wp_options` to get also the ones with `LanguageDependingOption`
                // phpcs:disable WordPress.DB
                $options = $wpdb->get_results(\sprintf("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE '%1\$s-banner-legal-%%' OR option_name = '%1\$s-banner-footer-design-link-target'", RCB_OPT_PREFIX), ARRAY_A);
                // phpcs:enable WordPress.DB
                if (\count($options) > 1) {
                    $currentLanguage = $compLanguage->getCurrentLanguageFallback();
                    $optionPrefix = RCB_OPT_PREFIX . '-banner-legal-';
                    $options = \array_combine(\array_map(function ($key) use($optionPrefix) {
                        return \strpos($key, $optionPrefix) === 0 ? \substr($key, \strlen($optionPrefix)) : $key;
                    }, \array_column($options, 'option_name')), \array_column($options, 'option_value'));
                    $isTargetBlank = ($options[RCB_OPT_PREFIX . '-banner-footer-design-link-target'] ?? '_blank') === '_blank';
                    foreach (['imprint', 'privacy-policy'] as $migrateType) {
                        $entryRef =& $entries[$migrateType === 'imprint' ? 'legalNotice' : 'privacyPolicy'];
                        $metaInputRef =& $entryRef['meta_input'];
                        $metaInputRef[self::META_NAME_PAGE_ID] = \intval($options[$migrateType] ?? 0);
                        $metaInputRef[self::META_NAME_EXTERNAL_URL] = $options[$migrateType . '-external-url'] ?? '';
                        $metaInputRef[self::META_NAME_IS_EXTERNAL_URL] = \boolval($options[$migrateType . '-is-external-url'] ?? \false);
                        $metaInputRef[self::META_NAME_HIDE_COOKIE_BANNER] = \boolval($options[$migrateType . '-hide'] ?? \true);
                        $metaInputRef[self::META_NAME_IS_TARGET_BLANK] = $isTargetBlank;
                        $labelTranslations = [];
                        if (\count($activeLangauges) > 0) {
                            foreach ($activeLangauges as $activeLangauge) {
                                $labelTranslations[$compLanguage->getWordPressCompatibleLanguageCode($activeLangauge)] = $options[$migrateType . '-label-' . $activeLangauge] ?? $entryRef['post_title'];
                            }
                            $backwardsCompatibilityFilter = function ($translation, $input, $sourceLocale, $targetLocale, $domain) use($labelTranslations) {
                                if ($domain === 'real-cookie-banner' && $translation === null && !empty($input) && \in_array($input, $labelTranslations, \true) && isset($labelTranslations[$targetLocale])) {
                                    return [$input, $labelTranslations[$targetLocale]];
                                }
                                return $translation;
                            };
                            $backwardsCompatibilityFilters[] = $backwardsCompatibilityFilter;
                            \add_filter('DevOwl/Multilingual/TranslateInput', $backwardsCompatibilityFilter, 10, 5);
                            $entryRef['post_title'] = $options[$migrateType . '-label-' . $currentLanguage] ?? $entryRef['post_title'];
                        } else {
                            $entryRef['post_title'] = $options[$migrateType . '-label'] ?? $entryRef['post_title'];
                        }
                    }
                }
            }
            foreach ($entries as $entry) {
                \wp_insert_post($entry, \true);
            }
            $privacyPolicyId = $entries['privacyPolicy']['meta_input'][self::META_NAME_PAGE_ID];
            if ($privacyPolicyId > 0) {
                PrivacyPolicyMentionUsage::recalculate($privacyPolicyId);
                PrivacyPolicy::recalculate($privacyPolicyId);
            }
            $td->teardown();
            foreach ($backwardsCompatibilityFilters as $f) {
                \remove_filter('DevOwl/Multilingual/TranslateInput', $f, 10);
            }
        };
        if (\did_action('init')) {
            $fn();
        } else {
            \add_action('init', $fn, 9);
        }
    }
    /**
     * When Settings > Privacy got adjusted, apply the new privacy policy to the banner links, too.
     *
     * @param int $old_value
     * @param int $new_value
     */
    public function update_option_wp_page_for_privacy_policy($old_value, $new_value)
    {
        $compLanguage = Core::getInstance()->getCompLanguage();
        if ($compLanguage !== null) {
            $new_value = $compLanguage->getOriginalPostId($new_value, 'page');
        }
        if (\get_post_status($new_value) === 'publish') {
            foreach ($this->getOrdered() as $bannerLink) {
                if ($bannerLink->metas[self::META_NAME_PAGE_TYPE] === SettingsBannerLink::PAGE_TYPE_PRIVACY_POLICY && !$bannerLink->metas[self::META_NAME_IS_EXTERNAL_URL]) {
                    \update_post_meta($bannerLink->ID, self::META_NAME_PAGE_ID, $new_value);
                    break;
                }
            }
        }
    }
    /**
     * When a page gets deleted, check if the value is our configured banner links and reset the value accordingly.
     *
     * @param number $postId
     */
    public function delete_post($postId)
    {
        foreach ($this->getOrdered() as $bannerLink) {
            if ($bannerLink->metas[self::META_NAME_PAGE_ID] === $postId && !$bannerLink->metas[self::META_NAME_IS_EXTERNAL_URL]) {
                \update_post_meta($bannerLink->ID, self::META_NAME_PAGE_ID, 0);
            }
        }
    }
    /**
     * When the privacy policy page gets adjusted, let's update the checklist if Real Cookie Banner
     * is mentioned in the privacy policy and if a privacy policy page is set.
     *
     * @param int $meta_id
     * @param int $object_id
     * @param string $meta_key
     * @param mixed $meta_value
     */
    public function added_or_updated_post_meta($meta_id, $object_id, $meta_key, $meta_value)
    {
        if ($meta_key === self::META_NAME_PAGE_ID && \get_post_type($object_id) === self::CPT_NAME) {
            $compLanguage = Core::getInstance()->getCompLanguage();
            $postId = $meta_value;
            if ($compLanguage !== null) {
                $postId = $compLanguage->getCurrentPostId($postId, 'page');
            }
            \add_action('shutdown', function () use($postId) {
                if (\get_post_meta($postId, self::META_NAME_PAGE_TYPE, \true) === SettingsBannerLink::PAGE_TYPE_PRIVACY_POLICY) {
                    PrivacyPolicyMentionUsage::recalculate($postId);
                    PrivacyPolicy::recalculate($postId);
                }
            });
        }
    }
    /**
     * A page got updated, check if it is our privacy policy and recalculate the checklist.
     *
     * @param int $postId
     */
    public function save_post_page($postId)
    {
        foreach ($this->getOrdered() as $bannerLink) {
            if ($bannerLink->metas[self::META_NAME_PAGE_TYPE] === SettingsBannerLink::PAGE_TYPE_PRIVACY_POLICY && $bannerLink->metas[self::META_NAME_PAGE_ID] === $postId && !$bannerLink->metas[self::META_NAME_IS_EXTERNAL_URL]) {
                PrivacyPolicyMentionUsage::recalculate($postId);
                PrivacyPolicy::recalculate($postId);
            }
        }
    }
    /**
     * Get singleton instance.
     *
     * @return BannerLink
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\settings\BannerLink() : self::$me;
    }
}
