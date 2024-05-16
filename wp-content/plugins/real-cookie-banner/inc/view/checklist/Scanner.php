<?php

namespace DevOwl\RealCookieBanner\view\checklist;

use DevOwl\RealCookieBanner\scanner\AutomaticScanStarter;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Is the scanner started once?
 * @internal
 */
class Scanner extends \DevOwl\RealCookieBanner\view\checklist\AbstractChecklistItem
{
    const IDENTIFIER = 'scanner';
    // Documented in AbstractChecklistItem
    public function isChecked()
    {
        return \get_option(AutomaticScanStarter::OPTION_NAME) === AutomaticScanStarter::STATUS_STARTED;
    }
    // Documented in AbstractChecklistItem
    public function getTitle()
    {
        return \__('Scan your website for services', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getDescription()
    {
        return \__('Scan your website to identify services used and external URLs that may transmit personal data and/or set cookies.', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getLink()
    {
        return '#/scanner';
    }
    // Documented in AbstractChecklistItem
    public function getLinkText()
    {
        return \__('Open scanner', RCB_TD);
    }
}
