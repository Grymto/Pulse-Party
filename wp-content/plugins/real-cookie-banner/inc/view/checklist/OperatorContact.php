<?php

namespace DevOwl\RealCookieBanner\view\checklist;

use DevOwl\RealCookieBanner\settings\General;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Are website operator details set?
 * @internal
 */
class OperatorContact extends \DevOwl\RealCookieBanner\view\checklist\AbstractChecklistItem
{
    const IDENTIFIER = 'operator-contact';
    // Documented in AbstractChecklistItem
    public function isChecked()
    {
        $operatorCountry = \get_option(General::SETTING_OPERATOR_COUNTRY);
        $operatorAddress = \get_option(General::SETTING_OPERATOR_CONTACT_ADDRESS);
        $operatorPhone = \get_option(General::SETTING_OPERATOR_CONTACT_PHONE);
        $operatorEmail = \get_option(General::SETTING_OPERATOR_CONTACT_EMAIL);
        $operatorFormId = \get_option(General::SETTING_OPERATOR_CONTACT_FORM_ID);
        return !empty($operatorCountry) && !empty($operatorAddress) && (!empty($operatorPhone) || !empty($operatorEmail) || $operatorFormId > 0);
    }
    // Documented in AbstractChecklistItem
    public function getTitle()
    {
        return \__('Set website operator details', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getDescription()
    {
        return \__('You as a website operator should provide your address and at least one contact option to fulfill your information obligations for self-hosted services.', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getLink()
    {
        return '#/settings';
    }
    // Documented in AbstractChecklistItem
    public function getLinkText()
    {
        return \__('Add operator details', RCB_TD);
    }
}
