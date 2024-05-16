<?php

namespace DevOwl\RealCookieBanner\view\checklist;

// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * The statistics should be opened once.
 * @internal
 */
class ViewStats extends \DevOwl\RealCookieBanner\view\checklist\AbstractChecklistItem
{
    const IDENTIFIER = 'view-stats';
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
        return \__('Check out statistics about consents', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getDescription()
    {
        return \__('All consents are documented, so that you can prove in doubt who, when and how a consent was given. Statistics about this will help you to e. g. extrapolate Google Analytics data. You can use them to estimate how many visitors have visited your website, even if not all of them have consented to use statistics services.', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getLink()
    {
        return '#/consent';
    }
    // Documented in AbstractChecklistItem
    public function needsPro()
    {
        return \true;
    }
    // Documented in AbstractChecklistItem
    public function getLinkText()
    {
        return \__('View stats', RCB_TD);
    }
}
