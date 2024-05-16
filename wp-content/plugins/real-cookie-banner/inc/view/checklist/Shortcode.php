<?php

namespace DevOwl\RealCookieBanner\view\checklist;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\view\customize\banner\StickyLinks;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * A legal shortcode was genearted once or the legal links were added to a manu.
 *
 * For backwards-compatibility this class is still named for "Shortcode" but it should be "Legal links" instead.
 * @internal
 */
class Shortcode extends \DevOwl\RealCookieBanner\view\checklist\AbstractChecklistItem
{
    const IDENTIFIER = 'legal-shortcodes';
    // Documented in AbstractChecklistItem
    public function isChecked()
    {
        return $this->getFromOption(self::IDENTIFIER) || Core::getInstance()->getBanner()->getCustomize()->getSetting(StickyLinks::SETTING_ENABLED);
    }
    // Documented in AbstractChecklistItem
    public function toggle($state)
    {
        return $this->persistStateToOption(self::IDENTIFIER, $state);
    }
    // Documented in AbstractChecklistItem
    public function getTitle()
    {
        return \__('Place legal links to your website', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getDescription()
    {
        return \__('Your website visitors must be able to view their consent history, change their consent, or withdraw their consent at any time. This must be as easy as giving consent. Therefore, the legal links must be included on every subpage of the website (e.g. in the footer).', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getLink()
    {
        return '#/consent/legal';
    }
    // Documented in AbstractChecklistItem
    public function getLinkText()
    {
        return \__('Create legal links', RCB_TD);
    }
}
