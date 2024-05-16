<?php

namespace DevOwl\RealCookieBanner\view\checklist;

use DevOwl\RealCookieBanner\view\Checklist;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Is a privacy policy page set?
 * @internal
 */
class PrivacyPolicy extends \DevOwl\RealCookieBanner\view\checklist\AbstractChecklistItem
{
    const IDENTIFIER = 'privacy-policy';
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
        return \__('Set privacy policy page', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getDescription()
    {
        return \__('Legally required pages must be accessible even if the cookie banner covers your website. Therefore, the privacy policy should be linked in the footer of the cookie banner.', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getLink()
    {
        return '#/settings';
    }
    // Documented in AbstractChecklistItem
    public function getLinkText()
    {
        return \__('Set privacy policy page', RCB_TD);
    }
    /**
     * Privacy policy got updated, let's update the checklist accordingly.
     *
     * @param int $postId
     */
    public static function recalculate($postId)
    {
        Checklist::getInstance()->toggle(self::IDENTIFIER, $postId > 0);
    }
}
