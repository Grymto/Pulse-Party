<?php

namespace DevOwl\RealCookieBanner\view\navmenu;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual\NavMenuList;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\view\Checklist;
use DevOwl\RealCookieBanner\view\checklist\Shortcode;
use Walker_Nav_Menu_Checklist;
use WP_Error;
use WP_Post;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Custom navigation menu links for the legal links.
 * @internal
 */
class NavMenuLinks
{
    use UtilsProvider;
    const METABOX_ID = 'rcb-nav-menu';
    const META_SUCCESS_MESSAGE_FIELD_ID = 'rcb-menu-item-success-message';
    const META_SUCCESS_MESSAGE_META_NAME = 'rcb-success-message';
    const NAVIGATION_ITEM_ID = 'rcb-nav-item';
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Get a list of available navigation menus so we can add a automatic "addition" process to our "Legal links" section.
     *
     * @param array $arr
     */
    public function revisionCurrent($arr)
    {
        $arr['nav_menus'] = (new NavMenuList(Core::getInstance()->getCompLanguage()))->get([$this, 'isMenuItem']);
        return $arr;
    }
    /**
     * Add existing links to our current menu.
     *
     * @param string $nav_menu_id
     * @return true|WP_Error
     */
    public function addLinksToMenu($nav_menu_id)
    {
        $compLanguage = Core::getInstance()->getCompLanguage();
        $nav_menu = (new NavMenuList($compLanguage))->getById($nav_menu_id, [$this, 'isMenuItem']);
        if ($nav_menu !== null) {
            $useLanguage = \count($nav_menu['languages']) > 0 ? $nav_menu['languages'][0]['code'] : null;
            $linkElements = $this->getLinkElements(\false, $useLanguage);
            $result = \true;
            $fnInsert = function () use($linkElements, $nav_menu, &$result) {
                foreach ($linkElements as $url => $element) {
                    $added = \wp_update_nav_menu_item($nav_menu['term_id'], 0, ['menu-item-title' => $element['linkText'], 'menu-item-url' => $url, 'menu-item-status' => 'publish']);
                    if (\is_wp_error($added)) {
                        $result = $added;
                        break;
                    } elseif (isset($element['meta'])) {
                        foreach ($element['meta'] as $key => $val) {
                            \update_post_meta($added, $key, $val);
                        }
                    }
                }
            };
            if ($compLanguage !== null && $compLanguage->isActive()) {
                $compLanguage->switchToLanguage($useLanguage, $fnInsert);
            } else {
                $fnInsert();
            }
            Checklist::getInstance()->toggle(Shortcode::IDENTIFIER, \true);
            return $result;
        } else {
            return new WP_Error('rcb_nav_menu_non_existing', \__('The navigation menu could not be found.', RCB_TD));
        }
    }
    /**
     * Get the available link elements.
     *
     * @param boolean $onlyLinks
     * @param string $language
     */
    public function getLinkElements($onlyLinks = \false, $language = null)
    {
        if ($onlyLinks) {
            return ['#consent-change', '#consent-history', '#consent-revoke'];
        }
        $td = Hooks::getInstance()->createTemporaryTextDomain();
        $result = [];
        $fnCalculateResult = function ($domain) use(&$result) {
            $result = ['#consent-change' => ['label' => \_x('Change privacy settings', 'legal-text', RCB_TD), 'linkText' => \_x('Change privacy settings', 'legal-text', $domain)], '#consent-history' => ['label' => \_x('Privacy settings history', 'legal-text', RCB_TD), 'linkText' => \_x('Privacy settings history', 'legal-text', $domain)], '#consent-revoke' => ['label' => \_x('Revoke consents', 'legal-text', RCB_TD), 'linkText' => \_x('Revoke consents', 'legal-text', $domain), 'meta' => [self::META_SUCCESS_MESSAGE_META_NAME => \_x('You have successfully revoked consent for services with its cookies and personal data processing. The page will be reloaded now!', 'legal-text', $domain)]]];
        };
        if ($language !== null) {
            $compLanguage = Core::getInstance()->getCompLanguage();
            $compLanguage->switchToLanguage($language, function () use($compLanguage, $fnCalculateResult) {
                $fnCalculateResult($compLanguage->getTemporaryTextDomainName());
            });
        } else {
            $fnCalculateResult(Hooks::TD_FORCED);
        }
        $td->teardown();
        return $result;
    }
    /**
     * Add custom meta fields to the nav menu field.
     *
     * @param int $item_id
     * @param object $item
     */
    public function wp_nav_menu_item_custom_fields($item_id, $item)
    {
        if ($this->isMenuItem($item) && $item->url === '#consent-revoke') {
            $compLanguage = Core::getInstance()->getCompLanguage();
            $useLanguage = $compLanguage !== null && $compLanguage->isActive() ? $compLanguage->getCurrentLanguageFallback() : null;
            $linkElements = $this->getLinkElements(\false, $useLanguage);
            $linkElement = $linkElements[$item->url];
            // Insert default values for newly created items
            if (!\metadata_exists('post', $item_id, self::META_SUCCESS_MESSAGE_META_NAME)) {
                foreach ($linkElement['meta'] as $key => $val) {
                    \update_post_meta($item_id, $key, $val);
                }
            }
            $successMessage = \get_post_meta($item_id, self::META_SUCCESS_MESSAGE_META_NAME, \true);
            echo \sprintf('<p class="description description-wide">
        <label for="%4$s-%1$d" >
            %2$s<br />
            <input type="text" class="widefat" id="%4$s-%1$d" 
                name="%4$s[%1$d]" value="%3$s"
            />
        </label>
    </p>', $item_id, \__('Success message', RCB_TD), $successMessage, self::META_SUCCESS_MESSAGE_FIELD_ID);
        }
    }
    /**
     * Save custom meta fields.
     *
     * @param int $menu_id
     * @param int $menu_item_db_id
     * @param array $args
     */
    public function wp_update_nav_menu_item($menu_id, $menu_item_db_id, $args)
    {
        $successMessage = $_POST[self::META_SUCCESS_MESSAGE_FIELD_ID][$menu_item_db_id] ?? null;
        if ($successMessage !== null) {
            $successMessage = \sanitize_text_field($successMessage);
            \update_post_meta($menu_item_db_id, self::META_SUCCESS_MESSAGE_META_NAME, $successMessage);
        }
        if (isset($args['menu-item-url']) && \in_array($args['menu-item-url'], $this->getLinkElements(\true), \true)) {
            Checklist::getInstance()->toggle(Shortcode::IDENTIFIER, \true);
        }
    }
    /**
     * Add the custom meta fields to the output navigation element.
     *
     * @param array $atts
     * @param WP_Post $item
     */
    public function nav_menu_link_attributes($atts, $item)
    {
        if ($this->isMenuItem($item) && $item->url === '#consent-revoke') {
            $atts['data-success-message'] = \get_post_meta($item->ID, self::META_SUCCESS_MESSAGE_META_NAME, \true);
        }
        return $atts;
    }
    /**
     * Register new metabox.
     */
    public function admin_head()
    {
        \add_meta_box(self::METABOX_ID, 'Real Cookie Banner', [$this, 'nav_menu_metabox'], 'nav-menus', 'side', 'default');
        // Create a script to automatically hide URL from the menu item
        echo '<script>
    jQuery(document).ready(function($) {
        const fnHide = function() {
            $(\'.edit-menu-item-url[value^="#consent"]\').parents(".field-url").hide();
        };
        fnHide();
        setInterval(fnHide, 1000);
    });
</script>';
    }
    /**
     * Output script to hide the "URL" input field in customizer.
     */
    public function customize_controls_head()
    {
        echo '<script>
    jQuery(document).ready(function($) {
        const fnHide = function() {
            $("input.edit-menu-item-url").filter(function() {
                return $(this).val().startsWith("#consent");
            }).parents("label").hide();
        };
        fnHide();
        setInterval(fnHide, 1000);
    });
</script>';
    }
    /**
     * Print the metabox.
     */
    public function nav_menu_metabox()
    {
        global $nav_menu_selected_id;
        $nav_items = [];
        foreach ($this->getLinkElements() as $value => $element) {
            $item = new \DevOwl\RealCookieBanner\view\navmenu\NavMenuLinkItem();
            $item->object_id = \esc_html($value);
            $item->ID = $item->object_id;
            $item->title = \esc_html($element['linkText']);
            $item->url = \esc_html($value);
            $nav_items[$element['label']] = $item;
        }
        $walker = new Walker_Nav_Menu_Checklist([]);
        echo \sprintf('<div id="rcb-links" class="rcblinksdiv">
    <div id="tabs-panel-rcb-links-all" class="tabs-panel tabs-panel-view-all tabs-panel-active">
        <ul id="rcb-linkschecklist" class="list:rcb-links categorychecklist form-no-clear">%s</ul>
    </div>
    <div class="button-controls">
        <span class="add-to-menu">
            <input type="submit" %s class="button-secondary submit-add-to-menu right" value="%s" name="add-rcb-links-menu-item" id="submit-rcb-links"/>
            <span class="spinner"></span>
       </span>
    </div>
</div>', \walk_nav_menu_tree(\array_map('wp_setup_nav_menu_item', $nav_items), 0, (object) ['walker' => $walker]), \disabled($nav_menu_selected_id, 0), \esc_attr(\__('Add to Menu')));
    }
    /**
     * Correct "Custom link" to "Real Cookie Banner" type label.
     *
     * @param WP_Post $menu_item
     */
    public function wp_setup_nav_menu_item($menu_item)
    {
        if ($this->isMenuItem($menu_item)) {
            $menu_item->type_label = 'Real Cookie Banner';
        }
        return $menu_item;
    }
    /**
     * Register custom item types in customizer.
     *
     * @param array $item_types Menu item types.
     */
    public function register_customize_nav_menu_item_types($item_types)
    {
        $item_types[] = ['title' => 'Real Cookie Banner', 'type_label' => 'Real Cookie Banner', 'type' => self::METABOX_ID, 'object' => self::NAVIGATION_ITEM_ID];
        return $item_types;
    }
    /**
     * Register custom item type links in customizer.
     *
     * @param array $items
     * @param string $type
     * @param string $object
     * @param integer $page
     */
    public function register_customize_nav_menu_items($items = [], $type = '', $object = '', $page = 0)
    {
        if (self::NAVIGATION_ITEM_ID !== $object) {
            return $items;
        }
        if (0 < $page) {
            return $items;
        }
        foreach ($this->getLinkElements() as $id => $element) {
            $items[] = ['id' => \str_replace('#', '', $id), 'title' => $element['linkText'], 'type_label' => 'Real Cookie Banner', 'url' => $id];
        }
        return $items;
    }
    /**
     * Check if a given object of a menu item is a `NavMenuLinkItem`.
     *
     * @param WP_Post $menu_item
     */
    public function isMenuItem($menu_item)
    {
        return isset($menu_item->object, $menu_item->url) && $menu_item->object === 'custom' && \in_array($menu_item->url, $this->getLinkElements(\true), \true);
    }
    /**
     * New instance.
     *
     * @codeCoverageIgnore
     */
    public static function instance()
    {
        return new \DevOwl\RealCookieBanner\view\navmenu\NavMenuLinks();
    }
}
