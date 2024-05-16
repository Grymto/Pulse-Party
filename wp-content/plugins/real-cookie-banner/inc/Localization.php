<?php

namespace DevOwl\RealCookieBanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual\AbstractSyncPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual\Sync;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Constants;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Localization as UtilsLocalization;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\lite\settings\TcfVendorConfiguration;
use DevOwl\RealCookieBanner\settings\BannerLink;
use DevOwl\RealCookieBanner\settings\Blocker;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\CookieGroup;
use DevOwl\RealCookieBanner\templates\TemplateConsumers;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * i18n management for backend and frontend.
 * @internal
 */
class Localization
{
    use UtilsProvider;
    use UtilsLocalization;
    /**
     * Keys of array which should be not translated with `translateArray`.
     */
    const COMMON_SKIP_KEYS = ['slug'];
    /**
     * Get the directory where the languages folder exists.
     *
     * @param string $type
     * @return string[]
     */
    protected function getPackageInfo($type)
    {
        if ($type === Constants::LOCALIZATION_BACKEND) {
            return [RCB_PATH . '/languages', RCB_TD];
        } else {
            return [RCB_PATH . '/' . Constants::LOCALIZATION_PUBLIC_JSON_I18N, RCB_TD];
        }
    }
    /**
     * Make our plugin multilingual with the help of `AbstractSyncPlugin` and `Sync`!
     * Also have a look at `BannerCustomize`, there are `LanguageDependingOption`'s.
     */
    public static function multilingual()
    {
        $compLanguage = \DevOwl\RealCookieBanner\Core::getInstance()->getCompLanguage();
        $sync = new Sync(\array_merge([Cookie::CPT_NAME => Cookie::SYNC_OPTIONS, Blocker::CPT_NAME => Blocker::SYNC_OPTIONS, BannerLink::CPT_NAME => BannerLink::SYNC_OPTIONS], \DevOwl\RealCookieBanner\Core::getInstance()->isPro() ? [TcfVendorConfiguration::CPT_NAME => TcfVendorConfiguration::SYNC_OPTIONS] : []), [CookieGroup::TAXONOMY_NAME => CookieGroup::SYNC_OPTIONS], $compLanguage);
        $compLanguage->setSync($sync);
        \add_filter('DevOwl/Multilingual/TranslateInput', [TemplateConsumers::getInstance(), 'translateInputFromTemplates'], 10, 5);
        // Translate some meta fields when they get copied to the other language
        if ($compLanguage instanceof AbstractSyncPlugin) {
            foreach (\array_values(\array_merge(Cookie::SYNC_META_COPY_ONCE, Blocker::SYNC_OPTIONS_COPY_ONCE, BannerLink::SYNC_META_COPY_ONCE)) as $translateMetaKey) {
                \add_filter('DevOwl/Multilingual/Copy/Meta/post/' . $translateMetaKey, function ($value) use($compLanguage) {
                    return $compLanguage->translateInput($value, ['legal-text'])[1];
                });
            }
        }
    }
}
