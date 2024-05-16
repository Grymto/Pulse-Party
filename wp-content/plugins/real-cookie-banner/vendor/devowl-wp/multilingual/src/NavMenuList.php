<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

use stdClass;
use WP_Term;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Get a list of registered and created nav menus and determine their language, too.
 * @internal
 */
class NavMenuList
{
    /**
     * Abstract language implementation.
     *
     * @var AbstractSyncPlugin
     */
    private $compLanguage;
    /**
     * C'tor.
     *
     * @param AbstractSyncPlugin $compLanguage
     * @codeCoverageIgnore
     */
    public function __construct($compLanguage)
    {
        $this->compLanguage = $compLanguage;
    }
    /**
     * See `$this::get()`.
     *
     * @param string $id
     * @param callable $checkAlreadyAppliedBy
     */
    public function getById($id, $checkAlreadyAppliedBy)
    {
        $nav_menus = $this->get($checkAlreadyAppliedBy);
        return \array_values(\array_filter($nav_menus, function ($nav) use($id) {
            return $nav['id'] === $id;
        }))[0] ?? null;
    }
    /**
     * Get a list of registered and created nav menus and determine their language, too.
     *
     * @param callable $checkAlreadyAppliedBy If given, `WP_Post`s are read for all menus and represented to a boolean in the result.
     *                                        For the new navigation menu it will be only one `WP_Post` instance be passed which
     *                                        `post_content` has the Gutenberg blocks inside.
     */
    public function get($checkAlreadyAppliedBy = null)
    {
        $result = [];
        $nav_menus = $this->getAllCreatedNavMenus();
        $locations = \get_registered_nav_menus();
        $menu_locations = \get_nav_menu_locations();
        foreach ($nav_menus as $nav_menu) {
            $row = ['id' => \md5('legacy_nav' . $nav_menu->term_id), 'type' => 'legacy_nav', 'term_id' => $nav_menu->term_id, 'name' => $nav_menu->name, 'locations' => [], 'languages' => $this->getLanguagesForMenu($nav_menu), 'edit_url' => \admin_url(\sprintf('nav-menus.php?action=edit&menu=%d', $nav_menu->term_id))];
            // Find location
            $locIndex = \array_search($nav_menu->term_id, $menu_locations, \true);
            if ($locIndex !== \false && isset($locations[$locIndex])) {
                $row['locations'][$locIndex] = $locations[$locIndex];
            }
            foreach ($row['languages'] as $language) {
                // Fix locations when they are virtual through languages (e.g. PolyLang)
                if (!empty($language['location'])) {
                    $row['locations'][$language['location']] = $locations[$language['location']];
                }
                // Fix locations when they are virtual linked to another nav menu (e.g. WPML)
                if (!empty($language['locationOf'])) {
                    $locIndex = \array_search($language['locationOf'], $menu_locations, \true);
                    if ($locIndex !== \false) {
                        $row['locations'][$locIndex] = $locations[$locIndex];
                    }
                }
            }
            // Check if menu contains already a given item by criteria
            if (\is_callable($checkAlreadyAppliedBy)) {
                $row['applied'] = \count(\array_filter(\wp_get_nav_menu_items($nav_menu, ['nopaging' => \true]), $checkAlreadyAppliedBy)) > 0;
            }
            $row['locations'] = (object) $row['locations'];
            $result[] = $row;
        }
        return $result;
    }
    /**
     * Get all created nav menus by iterating all languages.
     *
     * @return WP_Term[]
     */
    protected function getAllCreatedNavMenus()
    {
        $result = [];
        $compLanguage = $this->compInstance();
        if ($compLanguage !== null && $compLanguage->isActive()) {
            $compLanguage->iterateAllLanguagesContext(function () use(&$result) {
                foreach (\wp_get_nav_menus() as $nav_menu) {
                    $result[] = $nav_menu;
                }
            });
        } else {
            $result = \wp_get_nav_menus();
        }
        // Unique them
        $ids = \array_map(function ($term) {
            return $term->term_id;
        }, $result);
        return \array_values(\array_intersect_key($result, \array_unique($ids)));
    }
    /**
     * Get the languages a given menu is assigned to. This can also be multiple.
     *
     * @param WP_Term $menu
     * @return array
     */
    protected function getLanguagesForMenu($menu)
    {
        $result = [];
        $compLanguage = $this->compInstance();
        if ($compLanguage instanceof PolyLang) {
            // See https://wordpress.org/support/topic/get-nav-menu-id-in-a-specific-languange/#post-13810196
            $options = \get_option('polylang');
            $theme = \get_option('stylesheet');
            if (isset($options['nav_menus'], $options['nav_menus'][$theme])) {
                foreach ($options['nav_menus'][$theme] as $location => $languages) {
                    foreach ($languages as $language => $language_menu_id) {
                        if ($language_menu_id === $menu->term_id) {
                            $result[] = ['code' => $language, 'language' => $compLanguage->getTranslatedName($language), 'location' => $location];
                        }
                    }
                }
            }
        } elseif ($compLanguage instanceof WPML) {
            $nav_details = \apply_filters('wpml_element_language_details', null, ['element_id' => $menu->term_id, 'element_type' => 'nav_menu']);
            if ($nav_details !== null) {
                $language_code = $nav_details->language_code;
                $result[] = ['code' => $language_code, 'language' => $compLanguage->getTranslatedName($language_code), 'locationOf' => $compLanguage->getOriginalTermId($menu->term_id, 'nav_menu')];
            }
        }
        return $result;
    }
    /**
     * Get compatibility language instance.
     */
    public function compInstance()
    {
        return $this->compLanguage;
    }
}
