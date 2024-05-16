<?php

namespace DevOwl\RealCookieBanner\view\customize\banner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\AbstractCustomizePanel;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls\CssMarginInput;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls\Headline;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls\RangeInput;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\view\BannerCustomize;
use WP_Customize_Color_Control;
use WP_Customize_Image_Control;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Header design.
 * @internal
 */
class HeaderDesign
{
    use UtilsProvider;
    const SECTION = BannerCustomize::PANEL_MAIN . '-header-design';
    const HEADLINE_LOGO = self::SECTION . '-headline-logo';
    const HEADLINE_FONT = self::SECTION . '-headline-font';
    const HEADLINE_BORDER_BOTTOM = self::SECTION . '-headline-border-bottom';
    const SETTING = RCB_OPT_PREFIX . '-banner-header-design';
    const SETTING_INHERIT_BG = self::SETTING . '-inherit-bg';
    const SETTING_BG = self::SETTING . '-bg';
    const SETTING_INHERIT_TEXT_ALIGN = self::SETTING . '-inherit-text-align';
    const SETTING_TEXT_ALIGN = self::SETTING . '-text-align';
    const SETTING_PADDING = self::SETTING . '-padding';
    const SETTING_LOGO = self::SETTING . '-logo';
    const SETTING_LOGO_RETINA = self::SETTING . '-logo-retina';
    const SETTING_LOGO_MAX_HEIGHT = self::SETTING . '-logo-max-height';
    const SETTING_LOGO_POSITION = self::SETTING . '-logo-position';
    const SETTING_LOGO_MARGIN = self::SETTING . '-logo-margin';
    const SETTING_FONT_SIZE = self::SETTING . '-font-size';
    const SETTING_FONT_COLOR = self::SETTING . '-font-color';
    const SETTING_FONT_INHERIT_FAMILY = self::SETTING . '-font-inherit-family';
    const SETTING_FONT_FAMILY = self::SETTING . '-font-family';
    const SETTING_FONT_WEIGHT = self::SETTING . '-font-weight';
    const SETTING_BOTTOM_BORDER_WIDTH = self::SETTING . '-border-width';
    const SETTING_BOTTOM_BORDER_COLOR = self::SETTING . '-border-color';
    const DEFAULT_INHERIT_BG = \true;
    const DEFAULT_BG = '#f4f4f4';
    const DEFAULT_INHERIT_TEXT_ALIGN = \true;
    const DEFAULT_TEXT_ALIGN = 'center';
    const DEFAULT_PADDING = [17, 20, 15, 20];
    const DEFAULT_LOGO_MAX_HEIGHT = 40;
    const DEFAULT_LOGO_POSITION = 'left';
    const DEFAULT_LOGO_MARGIN = [5, 15, 5, 15];
    const DEFAULT_FONT_SIZE = 20;
    const DEFAULT_FONT_COLOR = '#2b2b2b';
    const DEFAULT_FONT_INHERIT_FAMILY = \true;
    const DEFAULT_FONT_FAMILY = \DevOwl\RealCookieBanner\view\customize\banner\Design::DEFAULT_FONT_FAMILY;
    const DEFAULT_FONT_WEIGHT = 'normal';
    const DEFAULT_BOTTOM_BORDER_WIDTH = 1;
    const DEFAULT_BOTTOM_BORDER_COLOR = '#efefef';
    /**
     * Return arguments for this section.
     */
    public function args()
    {
        return ['name' => 'headerDesign', 'title' => \__('Header', RCB_TD), 'controls' => [self::SETTING_INHERIT_BG => ['name' => 'inheritBg', 'label' => \__('Adopt background color', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_INHERIT_BG, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_BG => ['name' => 'bg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_INHERIT_TEXT_ALIGN => ['name' => 'inheritTextAlign', 'label' => \__('Adopt text align', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_INHERIT_TEXT_ALIGN, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_TEXT_ALIGN => ['name' => 'textAlign', 'label' => \__('Text align', RCB_TD), 'type' => 'select', 'choices' => \DevOwl\RealCookieBanner\view\customize\banner\BodyDesign::getTextAlignChoices(), 'setting' => ['default' => self::DEFAULT_TEXT_ALIGN]], self::SETTING_PADDING => ['class' => CssMarginInput::class, 'name' => 'padding', 'label' => \__('Padding', RCB_TD), 'description' => \__('Define inner distance of the header.', RCB_TD), 'dashicon' => 'editor-contract', 'setting' => ['default' => self::DEFAULT_PADDING]], self::HEADLINE_LOGO => ['class' => Headline::class, 'label' => \__('Logo', RCB_TD)], self::SETTING_LOGO => ['class' => WP_Customize_Image_Control::class, 'name' => 'logo', 'label' => \__('Image', RCB_TD), 'description' => \__('The recommended minimum height of your logo is <strong>128px</strong>. The width of your logo will be adjusted automatically.', RCB_TD)], self::SETTING_LOGO_RETINA => ['class' => WP_Customize_Image_Control::class, 'name' => 'logoRetina', 'label' => \__('Image (x2 for Retina)', RCB_TD)], self::SETTING_LOGO_MAX_HEIGHT => ['name' => 'logoMaxHeight', 'label' => \__('Size', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 40, 'max' => 200, 'step' => 0], 'setting' => ['default' => self::DEFAULT_LOGO_MAX_HEIGHT, 'sanitize_callback' => 'absint']], self::SETTING_LOGO_POSITION => ['name' => 'logoPosition', 'label' => \__('Alignment relative to headline', RCB_TD), 'type' => 'select', 'choices' => ['left' => \__('Left', RCB_TD), 'right' => \__('Right', RCB_TD), 'above' => \__('Above', RCB_TD)], 'setting' => ['default' => self::DEFAULT_LOGO_POSITION]], self::SETTING_LOGO_MARGIN => ['class' => CssMarginInput::class, 'name' => 'logoMargin', 'label' => \__('Margin', RCB_TD), 'description' => \__('Define the outer distance around the logo.', RCB_TD), 'setting' => ['default' => self::DEFAULT_LOGO_MARGIN]], self::HEADLINE_FONT => ['class' => Headline::class, 'label' => \__('Font', RCB_TD)], self::SETTING_FONT_SIZE => ['name' => 'fontSize', 'label' => \__('Size', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 10, 'max' => 30, 'step' => 0], 'setting' => ['default' => self::DEFAULT_FONT_SIZE, 'sanitize_callback' => 'absint']], self::SETTING_FONT_COLOR => ['name' => 'fontColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_FONT_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_FONT_INHERIT_FAMILY => ['name' => 'fontInheritFamily', 'label' => \__('Adopt font family', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_FONT_INHERIT_FAMILY, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_FONT_FAMILY => ['name' => 'fontFamily', 'label' => \__('Family', RCB_TD), 'type' => 'select', 'choices' => \array_combine(AbstractCustomizePanel::WEB_SAFE_FONT_FAMILY, AbstractCustomizePanel::WEB_SAFE_FONT_FAMILY), 'setting' => ['default' => self::DEFAULT_FONT_FAMILY]], self::SETTING_FONT_WEIGHT => ['name' => 'fontWeight', 'label' => \__('Font weight', RCB_TD), 'type' => 'select', 'choices' => \DevOwl\RealCookieBanner\view\customize\banner\BodyDesign::getFontWeightChoices(), 'setting' => ['default' => self::DEFAULT_FONT_WEIGHT]], self::HEADLINE_BORDER_BOTTOM => ['class' => Headline::class, 'label' => \__('Bottom border', RCB_TD), 'description' => \__('You can define an additional bottom border to separate the header from the body.', RCB_TD)], self::SETTING_BOTTOM_BORDER_WIDTH => ['name' => 'borderWidth', 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'step' => 1, 'max' => 100], 'label' => \__('Height', RCB_TD), 'setting' => ['default' => self::DEFAULT_BOTTOM_BORDER_WIDTH, 'sanitize_callback' => 'absint']], self::SETTING_BOTTOM_BORDER_COLOR => ['name' => 'borderColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BOTTOM_BORDER_COLOR, 'sanitize_callback' => 'sanitize_hex_color']]]];
    }
}
