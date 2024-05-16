<?php

namespace DevOwl\RealCookieBanner\view\customize\banner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\AbstractCustomizePanel;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls\CustomHTML;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls\RangeInput;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\view\BannerCustomize;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Mobile optimized customization.
 * @internal
 */
class Mobile
{
    use UtilsProvider;
    const SECTION = BannerCustomize::PANEL_MAIN . '-mobile';
    const CUSTOM_HTML_HIDE_HEADER_IGNORE_DUE_CLOSE_ICON = self::SECTION . '-custom-html-hide-header-due-close-icon';
    const CUSTOM_HTML_MOBILE_EXPERIENCE_IN_FREE_VERSION = self::SECTION . '-custom-html-mobile-experience-in-free-version-notice';
    const SETTING = RCB_OPT_PREFIX . '-banner-mobile';
    const SETTING_ENABLED = self::SETTING . '-enabled';
    const SETTING_MAX_HEIGHT = self::SETTING . '-max-height';
    const SETTING_HIDE_HEADER = self::SETTING . '-hide-header';
    const SETTING_ALIGNMENT = self::SETTING . '-alignment';
    const SETTING_SCALE_PERCENT = self::SETTING . '-scale-percent';
    const SETTING_SCALE_PERCENT_VERTICAL = self::SETTING . '-scale-percent-vertical';
    const DEFAULT_ENABLED = \true;
    const DEFAULT_MAX_HEIGHT = 400;
    const DEFAULT_HIDE_HEADER = \false;
    const DEFAULT_ALIGNMENT = 'bottom';
    const DEFAULT_SCALE_PERCENT = 90;
    const DEFAULT_SCALE_PERCENT_VERTICAL = -50;
    /**
     * Return arguments for this section.
     */
    public function args()
    {
        $textMobileExperienceInFreeVersion = \sprintf('<div class="notice notice-info inline below-h2 notice-alt" style="margin: 0; display: %s;"><p>%s</p></div>', $this->isPro() ? 'none' : 'block', \__('The cookie banner is responsive even in the free version and works on mobile devices. Only further settings for a better individual appearance on mobile devices are only included in the PRO version.', RCB_TD));
        $hideHeaderIgnoredDueCloseIcon = \sprintf('<div class="notice notice-warning inline below-h2 notice-alt" style="margin: 10px 0 0 0"><p>%s</p></div>', \__('The header <strong>will not be hidden</strong>, because otherwise there is no possibility to continue without consent in compliance with the ePrivacy Directive and the GDPR. Activate the button/link "Continue without consent" in the "Consent Options" section on level above to be able to hide the header on mobile devices!', RCB_TD));
        return ['name' => 'mobile', 'title' => \__('Mobile experience', RCB_TD), 'controls' => [self::CUSTOM_HTML_MOBILE_EXPERIENCE_IN_FREE_VERSION => ['class' => CustomHTML::class, 'name' => 'customHtmlMobileExperienceInFreeVersionNotice', 'description' => $textMobileExperienceInFreeVersion], self::SETTING_ENABLED => ['name' => 'enabled', 'type' => 'mobileSwitcher', 'setting' => ['default' => self::DEFAULT_ENABLED, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_MAX_HEIGHT => ['name' => 'maxHeight', 'label' => \__('Maximum height', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 200, 'max' => 900, 'step' => 0], 'setting' => ['default' => self::DEFAULT_MAX_HEIGHT, 'sanitize_callback' => 'absint']], self::SETTING_HIDE_HEADER => ['name' => 'hideHeader', 'label' => \__('Hide header', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_HIDE_HEADER, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::CUSTOM_HTML_HIDE_HEADER_IGNORE_DUE_CLOSE_ICON => ['class' => CustomHTML::class, 'name' => 'customHtmlMobileHideHeaderIgnoreDueCloseIcon', 'description' => $hideHeaderIgnoredDueCloseIcon], self::SETTING_ALIGNMENT => ['name' => 'alignment', 'label' => \__('Alignment', RCB_TD), 'type' => 'select', 'choices' => ['top' => \__('Top', RCB_TD), 'center' => \__('Center', RCB_TD), 'bottom' => \__('Bottom', RCB_TD)], 'setting' => ['default' => self::DEFAULT_ALIGNMENT]], self::SETTING_SCALE_PERCENT => ['name' => 'scalePercent', 'label' => \__('Scaling factor', RCB_TD), 'description' => \__('Elements in the cookie banner can be scaled smaller or larger relative to the desktop view. Make sure that all elements are still clearly legible so that you obtain valid consents.', RCB_TD), 'class' => RangeInput::class, 'unit' => '%', 'input_attrs' => ['min' => 70, 'max' => 110, 'step' => 0], 'setting' => ['default' => self::DEFAULT_SCALE_PERCENT, 'sanitize_callback' => 'absint']], self::SETTING_SCALE_PERCENT_VERTICAL => ['name' => 'scalePercentVertical', 'label' => \__('Vertical spacing scaling factor', RCB_TD), 'description' => \__('The vertical distance between elements can be decreased or increased relative to the desktop view.', RCB_TD), 'class' => RangeInput::class, 'unit' => '%', 'input_attrs' => ['min' => -70, 'max' => 10, 'step' => 0], 'setting' => ['default' => self::DEFAULT_SCALE_PERCENT_VERTICAL, 'sanitize_callback' => function ($value) {
            return \intval($value);
        }]]]];
    }
}
