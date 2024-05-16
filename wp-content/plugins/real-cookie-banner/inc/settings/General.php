<?php

namespace DevOwl\RealCookieBanner\settings;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\services\Blocker as ServicesBlocker;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\services\ServiceGroup;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\AbstractGeneral;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\BannerLink as SettingsBannerLink;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\Language;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual\Iso3166OneAlpha2;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\lite\settings\General as LiteGeneral;
use DevOwl\RealCookieBanner\overrides\interfce\settings\IOverrideGeneral;
use DevOwl\RealCookieBanner\Utils;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Utils as UtilsUtils;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * General settings.
 * @internal
 */
class General extends AbstractGeneral implements IOverrideGeneral
{
    use LiteGeneral;
    use UtilsProvider;
    const OPTION_GROUP = 'options';
    const SETTING_BANNER_ACTIVE = RCB_OPT_PREFIX . '-banner-active';
    const SETTING_BLOCKER_ACTIVE = RCB_OPT_PREFIX . '-blocker-active';
    const SETTING_OPERATOR_COUNTRY = RCB_OPT_PREFIX . '-operator-country';
    const SETTING_OPERATOR_CONTACT_ADDRESS = RCB_OPT_PREFIX . '-operator-contact-address';
    const SETTING_OPERATOR_CONTACT_PHONE = RCB_OPT_PREFIX . '-operator-contact-phone';
    const SETTING_OPERATOR_CONTACT_EMAIL = RCB_OPT_PREFIX . '-operator-contact-email';
    const SETTING_OPERATOR_CONTACT_FORM_ID = RCB_OPT_PREFIX . '-operator-contact-form-id';
    const SETTING_TERRITORIAL_LEGAL_BASIS = RCB_OPT_PREFIX . '-territorial-legal-basis';
    const SETTING_HIDE_PAGE_IDS = RCB_OPT_PREFIX . '-hide-page-ids';
    const SETTING_SET_COOKIES_VIA_MANAGER = RCB_OPT_PREFIX . '-set-cookies-via-manager';
    const DEFAULT_BANNER_ACTIVE = \false;
    const DEFAULT_BLOCKER_ACTIVE = \true;
    const DEFAULT_OPERATOR_CONTACT_ADDRESS = '';
    const DEFAULT_OPERATOR_CONTACT_PHONE = '';
    const DEFAULT_OPERATOR_CONTACT_EMAIL = '';
    const DEFAULT_OPERATOR_CONTACT_FORM_ID = 0;
    const DEFAULT_HIDE_PAGE_IDS = '';
    const DEFAULT_SET_COOKIES_VIA_MANAGER = 'none';
    /**
     * Singleton instance.
     *
     * @var General
     */
    private static $me = null;
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Initially `add_option` to avoid autoloading issues.
     */
    public function enableOptionsAutoload()
    {
        UtilsUtils::enableOptionAutoload(self::SETTING_BANNER_ACTIVE, self::DEFAULT_BANNER_ACTIVE, 'boolval');
        UtilsUtils::enableOptionAutoload(self::SETTING_BLOCKER_ACTIVE, self::DEFAULT_BLOCKER_ACTIVE, 'boolval');
        UtilsUtils::enableOptionAutoload(self::SETTING_OPERATOR_COUNTRY, $this->getDefaultOperatorCountry());
        UtilsUtils::enableOptionAutoload(self::SETTING_OPERATOR_CONTACT_ADDRESS, \html_entity_decode(\get_bloginfo('name')));
        UtilsUtils::enableOptionAutoload(self::SETTING_OPERATOR_CONTACT_PHONE, self::DEFAULT_OPERATOR_CONTACT_PHONE);
        UtilsUtils::enableOptionAutoload(self::SETTING_OPERATOR_CONTACT_EMAIL, $this->getDefaultOperatorContactEmail());
        UtilsUtils::enableOptionAutoload(self::SETTING_OPERATOR_CONTACT_FORM_ID, self::DEFAULT_OPERATOR_CONTACT_FORM_ID, 'intval');
        UtilsUtils::enableOptionAutoload(self::SETTING_TERRITORIAL_LEGAL_BASIS, \join(',', $this->getDefaultTerritorialLegalBasis()));
        $this->overrideEnableOptionsAutoload();
    }
    /**
     * Register settings.
     */
    public function register()
    {
        \register_setting(self::OPTION_GROUP, self::SETTING_BANNER_ACTIVE, ['type' => 'boolean', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_BLOCKER_ACTIVE, ['type' => 'boolean', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_OPERATOR_COUNTRY, ['type' => 'string', 'show_in_rest' => \true, 'show_in_rest' => ['schema' => ['type' => 'string', 'enum' => \array_merge(\array_keys(Iso3166OneAlpha2::getCodes()), [''])]]]);
        \register_setting(self::OPTION_GROUP, self::SETTING_OPERATOR_CONTACT_ADDRESS, ['type' => 'string', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_OPERATOR_CONTACT_PHONE, ['type' => 'string', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_OPERATOR_CONTACT_EMAIL, ['type' => 'string', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_OPERATOR_CONTACT_FORM_ID, ['type' => 'number', 'show_in_rest' => \true]);
        // WP < 5.3 does not support array types yet, so we need to store serialized
        \register_setting(self::OPTION_GROUP, self::SETTING_TERRITORIAL_LEGAL_BASIS, ['type' => 'string', 'show_in_rest' => \true]);
        $this->overrideRegister();
    }
    // Documented in AbstractGeneral
    public function isBannerActive()
    {
        return \get_option(self::SETTING_BANNER_ACTIVE);
    }
    // Documented in AbstractGeneral
    public function isBlockerActive()
    {
        return \get_option(self::SETTING_BLOCKER_ACTIVE);
    }
    // Documented in AbstractGeneral
    public function getTerritorialLegalBasis()
    {
        $option = \explode(',', \get_option(self::SETTING_TERRITORIAL_LEGAL_BASIS, ''));
        $option = \array_intersect($option, self::LEGAL_BASIS_ALLOWED);
        $option = \array_values($option);
        return \count($option) > 0 ? $option : [self::TERRITORIAL_LEGAL_BASIS_GDPR];
    }
    // Documented in AbstractGeneral
    public function getOperatorCountry()
    {
        return \get_option(self::SETTING_OPERATOR_COUNTRY, '');
    }
    // Documented in AbstractGeneral
    public function getOperatorContactAddress()
    {
        return \get_option(self::SETTING_OPERATOR_CONTACT_ADDRESS, self::DEFAULT_OPERATOR_CONTACT_ADDRESS);
    }
    // Documented in AbstractGeneral
    public function getOperatorContactPhone()
    {
        return \get_option(self::SETTING_OPERATOR_CONTACT_PHONE, self::DEFAULT_OPERATOR_CONTACT_PHONE);
    }
    // Documented in AbstractGeneral
    public function getOperatorContactEmail()
    {
        return \get_option(self::SETTING_OPERATOR_CONTACT_EMAIL, self::DEFAULT_OPERATOR_CONTACT_EMAIL);
    }
    // Documented in AbstractGeneral
    public function getServiceGroups()
    {
        return \array_map(function ($data) {
            return ServiceGroup::fromJson($data);
        }, \DevOwl\RealCookieBanner\settings\CookieGroup::getInstance()->toJson());
    }
    // Documented in AbstractGeneral
    public function getBlocker()
    {
        return \array_map(function ($data) {
            return ServicesBlocker::fromJson($data);
        }, \DevOwl\RealCookieBanner\settings\Blocker::getInstance()->toJson());
    }
    // Documented in AbstractGeneral
    public function getBannerLinks()
    {
        return \array_map(function ($data) {
            return SettingsBannerLink::fromJson($data);
        }, \DevOwl\RealCookieBanner\settings\BannerLink::getInstance()->toJson());
    }
    // Documented in AbstractGeneral
    public function getLanguages()
    {
        return \array_map(function ($data) {
            return Language::fromJson($data);
        }, Core::getInstance()->getCompLanguage()->getLanguageSwitcher());
    }
    // Documented in AbstractGeneral
    public function getOperatorContactFormId()
    {
        return \get_option(self::SETTING_OPERATOR_CONTACT_FORM_ID, 0);
    }
    // Documented in AbstractGeneral
    public function getOperatorContactFormUrl($default = \false)
    {
        $compLanguage = Core::getInstance()->getCompLanguage();
        $id = $this->getOperatorContactFormId();
        if ($id > 0) {
            $id = $compLanguage->getCurrentPostId($id, 'page');
            $permalink = Utils::getPermalink($id);
            if ($permalink !== \false) {
                return $permalink;
            }
        }
        return $default;
    }
    /**
     * Get default privacy policy post ID.
     */
    public function getDefaultPrivacyPolicy()
    {
        $privacyPolicy = \intval(\get_option('wp_page_for_privacy_policy', 0));
        return \in_array(\get_post_status($privacyPolicy), ['draft', 'publish'], \true) ? $privacyPolicy : 0;
    }
    /**
     * Get default operator contact email.
     */
    public function getDefaultOperatorContactEmail()
    {
        return \get_bloginfo('admin_email');
    }
    /**
     * Get default operator country. We try to calculate the country from the blog language.
     */
    public function getDefaultOperatorCountry()
    {
        $locale = \strtoupper(\get_locale());
        $potentialLocales = \array_reverse(\explode('_', $locale));
        $isoCodes = \array_keys(Iso3166OneAlpha2::getCodes());
        foreach ($potentialLocales as $pl) {
            if (\in_array($pl, $isoCodes, \true)) {
                return $pl;
            }
        }
        return '';
    }
    /**
     * Get default territorial legal basis.
     */
    public function getDefaultTerritorialLegalBasis()
    {
        $country = $this->getDefaultOperatorCountry();
        $legalBasis = [self::TERRITORIAL_LEGAL_BASIS_GDPR];
        switch ($country) {
            case 'CH':
                $legalBasis[] = self::TERRITORIAL_LEGAL_BASIS_DSG_SWITZERLAND;
                break;
            default:
                break;
        }
        return $legalBasis;
    }
    /**
     * When a page gets deleted, check if the value is our configured contact form page and reset the value accordingly.
     *
     * @param number $postId
     */
    public function delete_post($postId)
    {
        $contactFormId = \get_option(self::SETTING_OPERATOR_CONTACT_FORM_ID);
        if ($postId === $contactFormId) {
            \update_option(self::SETTING_OPERATOR_CONTACT_FORM_ID, self::DEFAULT_OPERATOR_CONTACT_FORM_ID);
        }
    }
    /**
     * Get singleton instance.
     *
     * @return General
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\settings\General() : self::$me;
    }
}
