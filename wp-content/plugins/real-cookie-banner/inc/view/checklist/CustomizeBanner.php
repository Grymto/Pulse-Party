<?php

namespace DevOwl\RealCookieBanner\view\checklist;

use DevOwl\RealCookieBanner\view\BannerCustomize;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * The customize banner panel was opened once.
 * @internal
 */
class CustomizeBanner extends \DevOwl\RealCookieBanner\view\checklist\AbstractChecklistItem
{
    const IDENTIFIER = 'customize-banner';
    // Documented in AbstractChecklistItem
    public function isChecked()
    {
        return $this->getFromOption(self::IDENTIFIER);
    }
    // Documented in AbstractChecklistItem
    public function toggle($state)
    {
        return $this->persistStateToOption(self::IDENTIFIER, $state);
    }
    // Documented in AbstractChecklistItem
    public function getTitle()
    {
        return \__('Customize the design of the cookie banner', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getDescription()
    {
        return \__('You can customize the design of the cookie banner. Match it perfectly to your website!', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getLink()
    {
        return \add_query_arg(['autofocus[panel]' => BannerCustomize::PANEL_MAIN, 'return' => \wp_get_raw_referer()], \admin_url('customize.php'));
    }
    // Documented in AbstractChecklistItem
    public function getLinkText()
    {
        return \__('Start customizing', RCB_TD);
    }
}
