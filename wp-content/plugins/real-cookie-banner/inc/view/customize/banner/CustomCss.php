<?php

namespace DevOwl\RealCookieBanner\view\customize\banner;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\view\BannerCustomize;
use WP_Customize_Code_Editor_Control;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Custom CSS.
 * @internal
 */
class CustomCss
{
    use UtilsProvider;
    const SECTION = BannerCustomize::PANEL_MAIN . '-custom-css';
    const SETTING = RCB_OPT_PREFIX . '-banner-custom-css';
    const SETTING_ANTI_AD_BLOCKER = self::SETTING . '-anti-ad-blocker';
    const SETTING_CSS = self::SETTING . '-css';
    const DEFAULT_ANTI_AD_BLOCKER = 'y';
    const DEFAULT_CSS = '';
    /**
     * Return arguments for this section.
     */
    public function args()
    {
        return ['name' => 'customCss', 'title' => \__('Custom CSS', RCB_TD), 'controls' => [self::SETTING_CSS => ['class' => WP_Customize_Code_Editor_Control::class, 'name' => 'css', 'label' => \__('Custom CSS', RCB_TD), 'description' => \__('Please use this text input for custom CSS explicitly for your cookie banner. Custom CSS outside this field, e.g. in your theme options, will not work due to anti-adblocker optimization. Use class names like <code>rcb-headline</code> that you only see here in the customizer preview, and not auto-generated class names that look like <code>ac04ddfa165</code>.', RCB_TD), 'code_type' => 'text/css', 'setting' => ['default' => self::DEFAULT_CSS, 'allowEmpty' => \true]], self::SETTING_ANTI_AD_BLOCKER => ['name' => 'antiAdBlocker', 'label' => \__('Anti-Adblocker optimization', RCB_TD), 'description' => \__('Some adblockers are able to block cookie banners. This means that these website visitors will never see the cookie banner and you cannot get consent from them to use more than the essential services. We have implemented several mechanisms that try to remove any technical indicators that adblockers can use to block the cookie banner. This includes CSS selections for styles.', RCB_TD), 'type' => 'radio', 'choices' => ['y' => \__('Enable', RCB_TD), 'n' => \__('Disable', RCB_TD)], 'setting' => ['default' => self::DEFAULT_ANTI_AD_BLOCKER]]]];
    }
}
