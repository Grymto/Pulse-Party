<?php

namespace DevOwl\RealCookieBanner\view\checklist;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\DemoEnvironment;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Is the plugin license activated?
 * @internal
 */
class License extends \DevOwl\RealCookieBanner\view\checklist\AbstractChecklistItem
{
    const IDENTIFIER = 'license';
    // Documented in AbstractChecklistItem
    public function isChecked()
    {
        if (DemoEnvironment::getInstance()->isDemoEnv()) {
            return \true;
        }
        return !empty(Core::getInstance()->getRpmInitiator()->getPluginUpdater()->getCurrentBlogLicense()->getActivation()->getCode());
    }
    // Documented in AbstractChecklistItem
    public function getTitle()
    {
        return \__('Activate your license', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getDescription()
    {
        return \__('Only users with an activated license get updates and are always up-to-date on legal changes.', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getLink()
    {
        return '#/licensing';
    }
    // Documented in AbstractChecklistItem
    public function getLinkText()
    {
        return \__('Activate now', RCB_TD);
    }
}
