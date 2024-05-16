<?php

namespace DevOwl\RealCookieBanner\view\navmenu;

// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Item for `NavMenuLinks`.
 * @internal
 */
class NavMenuLinkItem
{
    public $title;
    public $url;
    // Same as `$db_id`
    public $ID = 0;
    public $db_id = 0;
    public $object = \DevOwl\RealCookieBanner\view\navmenu\NavMenuLinks::NAVIGATION_ITEM_ID;
    public $object_id;
    public $menu_item_parent = 0;
    public $type = 'custom';
    public $target = '';
    public $attr_title = '';
    public $classes = [];
    public $xfn = '';
}
