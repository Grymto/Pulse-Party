<?php

namespace DevOwl\RealCookieBanner\view\checklist;

// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Add first content blocker to website.
 * @internal
 */
class AddBlocker extends \DevOwl\RealCookieBanner\view\checklist\AbstractChecklistItem
{
    const IDENTIFIER = 'add-blocker';
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
        return \__('Create a content blocker', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getDescription()
    {
        return \__('Visitors who do not agree to the cookies and data processing of e.g. YouTube will not be able to load YouTube videos on your website. Content blockers will automatically replace this content and ask the visitor for consent again.', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getLink()
    {
        return '#/blocker/new';
    }
    // Documented in AbstractChecklistItem
    public function getLinkText()
    {
        return \__('Add content blocker', RCB_TD);
    }
}
