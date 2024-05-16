<?php

namespace DevOwl\RealCookieBanner\import;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls\Headline;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\lite\settings\TcfVendorConfiguration;
use DevOwl\RealCookieBanner\settings\BannerLink;
use DevOwl\RealCookieBanner\settings\Blocker;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\CookieGroup;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Handle export data.
 * @internal
 */
class Export
{
    use UtilsProvider;
    const RELEVANT_GROUP_TERM_KEYS = ['term_id', 'slug', 'name', 'description'];
    const RELEVANT_COOKIE_POST_KEYS = ['ID', 'post_name', 'post_content', 'post_status', 'post_title', 'metas'];
    const RELEVANT_BLOCKER_POST_KEYS = ['ID', 'post_name', 'post_content', 'post_status', 'post_title', 'metas'];
    const RELEVANT_BANNER_LINK_POST_KEYS = ['ID', 'post_name', 'post_title', 'metas'];
    const RELEVANT_TCF_VENDOR_CONFIGURATION_POST_KEYS = ['ID', 'post_name', 'post_status', 'post_title', 'metas'];
    const EXPORT_POST_STATI = ['publish', 'private', 'draft'];
    private $data = [];
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Append settings to the output of the export.
     */
    public function appendSettings()
    {
        $this->data['settings'] = \DevOwl\RealCookieBanner\import\Import::instance([])->optionsMap();
        return $this;
    }
    /**
     * Append cookie groups to the output of the export.
     */
    public function appendCookieGroups()
    {
        $this->data['cookieGroups'] = $this->getExportGroups();
        return $this;
    }
    /**
     * Append cookies to the output of the export.
     */
    public function appendCookies()
    {
        $groupsKnown = isset($this->data['cookieGroups']);
        $groups = $groupsKnown ? $this->data['cookieGroups'] : $this->getExportGroups();
        $this->data['cookies'] = $this->getExportCookies($groups);
        return $this;
    }
    /**
     * Append content blocker to the output of the export.
     */
    public function appendBlocker()
    {
        $this->data['blocker'] = $this->getExportBlocker();
        return $this;
    }
    /**
     * Append content blocker to the output of the export.
     */
    public function appendBannerLinks()
    {
        $this->data['bannerLinks'] = $this->getExportBannerLinks();
        return $this;
    }
    /**
     * Append TCF vendor configurations (if available) to the output of the export.
     */
    public function appendTcfVendorConfigurations()
    {
        $this->data['tcfVendorConfigurations'] = $this->getTcfVendorConfigurations();
        return $this;
    }
    /**
     * Append customize banner options to the output of the export.
     */
    public function appendCustomizeBanner()
    {
        $this->data['customizeBanner'] = Core::getInstance()->getBanner()->getCustomize()->localizeValues([Headline::class])['customizeValuesBanner'];
        return $this;
    }
    /**
     * Get the exported data as array.
     *
     * @return array
     */
    public function finish()
    {
        return $this->data;
    }
    /**
     * Get groups for export.
     */
    protected function getExportGroups()
    {
        $terms = \get_terms(Core::getInstance()->queryArguments(['taxonomy' => CookieGroup::TAXONOMY_NAME, 'orderby' => 'meta_value_num', 'order' => 'ASC', 'hide_empty' => \false, 'meta_query' => [['key' => 'order', 'type' => 'NUMERIC']]], 'exportCookieGroups'));
        $result = [];
        foreach ($terms as $term) {
            // $meta = get_term_meta($term->term_id);
            // Ignore meta as it can currently hold only the order and this is not exported
            $rowResult = [];
            foreach (self::RELEVANT_GROUP_TERM_KEYS as $key) {
                $rowResult[$key] = $term->{$key};
            }
            $result[] = $rowResult;
        }
        return $result;
    }
    /**
     * Get cookies for export.
     *
     * @param array $groups Result of getExportGroups()
     * @param boolean $areGroupsKnown If false, all cookies gets put to an empty group
     */
    protected function getExportCookies($groups)
    {
        $result = [];
        foreach ($groups as $group) {
            $cookies = Cookie::getInstance()->getOrdered($group['term_id'], \false, \get_posts(Core::getInstance()->queryArguments(['post_type' => Cookie::CPT_NAME, 'orderby' => ['menu_order' => 'ASC', 'ID' => 'DESC'], 'numberposts' => -1, 'nopaging' => \true, 'tax_query' => [['taxonomy' => CookieGroup::TAXONOMY_NAME, 'terms' => $group['term_id'], 'include_children' => \false]], 'post_status' => self::EXPORT_POST_STATI], 'cookiesExport')));
            foreach ($cookies as $cookie) {
                $rowResult = ['group' => $group['slug']];
                foreach (self::RELEVANT_COOKIE_POST_KEYS as $key) {
                    $rowResult[$key] = $cookie->{$key};
                }
                if (isset($rowResult['metas'][Blocker::META_NAME_PRESET_ID])) {
                    $version = \get_post_meta($cookie->ID, Blocker::META_NAME_PRESET_VERSION, \true);
                    if ($version > 0) {
                        $rowResult['metas'][Blocker::META_NAME_PRESET_VERSION] = $version;
                    }
                }
                $result[] = $rowResult;
            }
        }
        return $result;
    }
    /**
     * Get banner links for export.
     */
    protected function getExportBannerLinks()
    {
        $posts = BannerLink::getInstance()->getOrdered(\false, \get_posts(Core::getInstance()->queryArguments(['post_type' => BannerLink::CPT_NAME, 'orderby' => ['ID' => 'DESC'], 'numberposts' => -1, 'nopaging' => \true, 'post_status' => self::EXPORT_POST_STATI], 'bannerLinkExport')));
        $result = [];
        foreach ($posts as $post) {
            $rowResult = [];
            foreach (self::RELEVANT_BANNER_LINK_POST_KEYS as $key) {
                $rowResult[$key] = $post->{$key};
            }
            $result[] = $rowResult;
        }
        return $result;
    }
    /**
     * Get blocker for export.
     */
    protected function getExportBlocker()
    {
        $posts = Blocker::getInstance()->getOrdered(\false, \get_posts(Core::getInstance()->queryArguments(['post_type' => Blocker::CPT_NAME, 'orderby' => ['ID' => 'DESC'], 'numberposts' => -1, 'nopaging' => \true, 'post_status' => self::EXPORT_POST_STATI], 'blockerExport')));
        $result = [];
        foreach ($posts as $post) {
            $rowResult = [];
            foreach (self::RELEVANT_BLOCKER_POST_KEYS as $key) {
                $rowResult[$key] = $post->{$key};
                // Cookies / TCF vendors should be resolved as post_name instead of ID
                if ($key === 'metas') {
                    foreach ([Blocker::META_NAME_SERVICES, Blocker::META_NAME_TCF_VENDORS] as $metaName) {
                        foreach ($rowResult[$key][$metaName] as $cookieIdx => $cookieId) {
                            $rowResult[$key][$metaName][$cookieIdx] = \get_post($cookieId)->post_name;
                        }
                    }
                }
            }
            if (isset($rowResult['metas']['visualThumbnail'])) {
                unset($rowResult['metas']['visualThumbnail']);
            }
            if (isset($rowResult['metas'][Blocker::META_NAME_PRESET_ID])) {
                $version = \get_post_meta($post->ID, Blocker::META_NAME_PRESET_VERSION, \true);
                if ($version > 0) {
                    $rowResult['metas'][Blocker::META_NAME_PRESET_VERSION] = $version;
                }
            }
            $result[] = $rowResult;
        }
        return $result;
    }
    /**
     * Get TCF vendor configurations for export.
     */
    protected function getTcfVendorConfigurations()
    {
        if (!$this->isPro()) {
            return [];
        }
        $posts = TcfVendorConfiguration::getInstance()->getOrdered(\false, \get_posts(Core::getInstance()->queryArguments(['post_type' => TcfVendorConfiguration::CPT_NAME, 'orderby' => ['ID' => 'DESC'], 'numberposts' => -1, 'nopaging' => \true, 'post_status' => self::EXPORT_POST_STATI], 'tcfVendorConfigurationsExport')));
        $result = [];
        foreach ($posts as $post) {
            $rowResult = [];
            foreach (self::RELEVANT_TCF_VENDOR_CONFIGURATION_POST_KEYS as $key) {
                $rowResult[$key] = $post->{$key};
            }
            $result[] = $rowResult;
        }
        return $result;
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     */
    public static function instance()
    {
        return new \DevOwl\RealCookieBanner\import\Export();
    }
}
