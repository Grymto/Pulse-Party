<?php

namespace DevOwl\RealCookieBanner\view\checklist;

use DevOwl\RealCookieBanner\settings\General;
use DevOwl\RealCookieBanner\view\Checklist;
use WP_REST_Request;
use WP_REST_Response;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Banner should be activated once.
 * @internal
 */
class ActivateBanner extends \DevOwl\RealCookieBanner\view\checklist\AbstractChecklistItem
{
    const IDENTIFIER = 'activate-banner';
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
        return \__('Activate Cookie Banner and Content Blocker', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getDescription()
    {
        return \__('Once everything is set up, you can display the cookie banner to your visitors.', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getLink()
    {
        return '#/settings';
    }
    // Documented in AbstractChecklistItem
    public function getLinkText()
    {
        return \__('Activate', RCB_TD);
    }
    /**
     * Settings got saved, let's check the checklist item.
     *
     * @param WP_REST_Response $response
     * @param WP_REST_Request $request
     */
    public static function settings_updated($response, $request)
    {
        if ($request->get_param(General::SETTING_BANNER_ACTIVE) === \true) {
            Checklist::getInstance()->toggle(self::IDENTIFIER, \true);
        }
        return $response;
    }
}
