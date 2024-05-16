<?php

namespace DevOwl\RealCookieBanner\view\checklist;

use DevOwl\RealCookieBanner\view\Checklist;
use WP_REST_Response;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Save settings for the first time.
 * @internal
 */
class SaveSettings extends \DevOwl\RealCookieBanner\view\checklist\AbstractChecklistItem
{
    const IDENTIFIER = 'save-settings';
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
        return \__('Adjust the settings of the cookie banner', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getDescription()
    {
        return \__('Decide which features should be enabled in your cookie banner depending on your needs.', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getLink()
    {
        return '#/settings';
    }
    // Documented in AbstractChecklistItem
    public function getLinkText()
    {
        return \__('Manage settings', RCB_TD);
    }
    /**
     * Settings got saved, let's check the checklist item.
     *
     * @param WP_REST_Response $response
     */
    public static function settings_updated($response)
    {
        Checklist::getInstance()->toggle(self::IDENTIFIER, \true);
        // Cleanup transients so they get regenerated
        \wp_rcb_invalidate_templates_cache(\true);
        return $response;
    }
}
