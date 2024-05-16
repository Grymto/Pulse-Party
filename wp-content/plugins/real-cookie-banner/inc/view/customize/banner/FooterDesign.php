<?php

namespace DevOwl\RealCookieBanner\view\customize\banner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\AbstractCustomizePanel;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls\CssMarginInput;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls\Headline;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls\RangeInput;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\view\BannerCustomize;
use WP_Customize_Color_Control;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Footer design.
 * @internal
 */
class FooterDesign
{
    use UtilsProvider;
    const SECTION = BannerCustomize::PANEL_MAIN . '-footer-design';
    const HEADLINE_FONT = self::SECTION . '-headline-font';
    const HEADLINE_LINK_HOVER = self::SECTION . '-headline-link-hover';
    const HEADLINE_BORDER_TOP = self::SECTION . '-headline-border-top';
    const HEADLINE_LANGUAGE_SWITCHER = self::SECTION . '-headline-language-switcher';
    const SETTING = RCB_OPT_PREFIX . '-banner-footer-design';
    const SETTING_POWERED_BY_LINK = self::SETTING . '-powered-by-link';
    const SETTING_INHERIT_BG = self::SETTING . '-inherit-bg';
    const SETTING_BG = self::SETTING . '-bg';
    const SETTING_INHERIT_TEXT_ALIGN = self::SETTING . '-inherit-text-align';
    const SETTING_TEXT_ALIGN = self::SETTING . '-text-align';
    const SETTING_PADDING = self::SETTING . '-padding';
    const SETTING_FONT_SIZE = self::SETTING . '-font-size';
    const SETTING_FONT_COLOR = self::SETTING . '-font-color';
    const SETTING_FONT_INHERIT_FAMILY = self::SETTING . '-font-inherit-family';
    const SETTING_FONT_FAMILY = self::SETTING . '-font-family';
    const SETTING_FONT_WEIGHT = self::SETTING . '-font-weight';
    const SETTING_HOVER_FONT_COLOR = self::SETTING . '-hover-font-color';
    const SETTING_TOP_BORDER_WIDTH = self::SETTING . '-border-width';
    const SETTING_TOP_BORDER_COLOR = self::SETTING . '-border-color';
    const SETTING_LANGUAGE_SWITCHER = self::SETTING . '-ls';
    const DEFAULT_INHERIT_BG = \false;
    const DEFAULT_BG = '#fcfcfc';
    const DEFAULT_INHERIT_TEXT_ALIGN = \true;
    const DEFAULT_TEXT_ALIGN = 'center';
    const DEFAULT_PADDING = [10, 20, 15, 20];
    const DEFAULT_FONT_SIZE = 14;
    const DEFAULT_FONT_COLOR = '#757474';
    const DEFAULT_FONT_INHERIT_FAMILY = \true;
    const DEFAULT_FONT_FAMILY = \DevOwl\RealCookieBanner\view\customize\banner\Design::DEFAULT_FONT_FAMILY;
    const DEFAULT_FONT_WEIGHT = 'normal';
    const DEFAULT_HOVER_FONT_COLOR = '#2b2b2b';
    const DEFAULT_TOP_BORDER_WIDTH = 1;
    const DEFAULT_TOP_BORDER_COLOR = '#efefef';
    const DEFAULT_LANGUAGE_SWITCHER = 'flags';
    /**
     * Return arguments for this section.
     */
    public function args()
    {
        return ['name' => 'footerDesign', 'title' => \__('Footer', RCB_TD), 'controls' => [self::SETTING_POWERED_BY_LINK => ['name' => 'poweredByLink', 'label' => \__('Show "powered by" link', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => $this->isPro(), 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_INHERIT_BG => ['name' => 'inheritBg', 'label' => \__('Adopt background color', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_INHERIT_BG, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_BG => ['name' => 'bg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_INHERIT_TEXT_ALIGN => ['name' => 'inheritTextAlign', 'label' => \__('Adopt text align', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_INHERIT_TEXT_ALIGN, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_TEXT_ALIGN => ['name' => 'textAlign', 'label' => \__('Text align', RCB_TD), 'type' => 'select', 'choices' => \DevOwl\RealCookieBanner\view\customize\banner\BodyDesign::getTextAlignChoices(), 'setting' => ['default' => self::DEFAULT_TEXT_ALIGN]], self::SETTING_PADDING => ['class' => CssMarginInput::class, 'name' => 'padding', 'label' => \__('Padding', RCB_TD), 'description' => \__('Define inner distance of the header.', RCB_TD), 'dashicon' => 'editor-contract', 'setting' => ['default' => self::DEFAULT_PADDING]], self::HEADLINE_FONT => ['class' => Headline::class, 'label' => \__('Font', RCB_TD)], self::SETTING_FONT_SIZE => ['name' => 'fontSize', 'label' => \__('Size', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 10, 'max' => 30, 'step' => 0], 'setting' => ['default' => self::DEFAULT_FONT_SIZE, 'sanitize_callback' => 'absint']], self::SETTING_FONT_COLOR => ['name' => 'fontColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_FONT_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_FONT_INHERIT_FAMILY => ['name' => 'fontInheritFamily', 'label' => \__('Adopt font family', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_FONT_INHERIT_FAMILY, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_FONT_FAMILY => ['name' => 'fontFamily', 'label' => \__('Family', RCB_TD), 'type' => 'select', 'choices' => \array_combine(AbstractCustomizePanel::WEB_SAFE_FONT_FAMILY, AbstractCustomizePanel::WEB_SAFE_FONT_FAMILY), 'setting' => ['default' => self::DEFAULT_FONT_FAMILY]], self::SETTING_FONT_WEIGHT => ['name' => 'fontWeight', 'label' => \__('Font weight', RCB_TD), 'type' => 'select', 'choices' => \DevOwl\RealCookieBanner\view\customize\banner\BodyDesign::getFontWeightChoices(), 'setting' => ['default' => self::DEFAULT_FONT_WEIGHT]], self::HEADLINE_LINK_HOVER => ['class' => Headline::class, 'label' => \__('Transition on hover', RCB_TD), 'level' => 3, 'isSubHeadline' => \true, 'description' => \__('When the user moves the mouse over the footer links, it changes its color.', RCB_TD)], self::SETTING_HOVER_FONT_COLOR => ['name' => 'hoverFontColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_HOVER_FONT_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_BORDER_TOP => ['class' => Headline::class, 'label' => \__('Top border', RCB_TD), 'description' => \__('You can define an additional top border to separate the footer from the body.', RCB_TD)], self::SETTING_TOP_BORDER_WIDTH => ['name' => 'borderWidth', 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'step' => 1, 'max' => 100], 'label' => \__('Height', RCB_TD), 'setting' => ['default' => self::DEFAULT_TOP_BORDER_WIDTH, 'sanitize_callback' => 'absint']], self::SETTING_TOP_BORDER_COLOR => ['name' => 'borderColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_TOP_BORDER_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_LANGUAGE_SWITCHER => ['class' => Headline::class, 'label' => \__('Language switcher', RCB_TD), 'name' => 'footerLanguageSwitcher'], self::SETTING_LANGUAGE_SWITCHER => ['name' => 'languageSwitcher', 'label' => \__('Language switcher', RCB_TD), 'type' => 'radio', 'choices' => ['disabled' => \__('Disabled', RCB_TD), 'flags' => \__('Show with flags', RCB_TD), 'no-flags' => \__('Show without flags', RCB_TD)], 'setting' => ['default' => self::DEFAULT_LANGUAGE_SWITCHER]]]];
    }
}
