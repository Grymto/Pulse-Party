<?php

namespace DevOwl\RealCookieBanner\view\customize\banner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\AbstractCustomizePanel;
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
 * Cookie banner design.
 * @internal
 */
class Design
{
    use UtilsProvider;
    const SECTION = BannerCustomize::PANEL_MAIN . '-design';
    const HEADLINE_FONT = self::SECTION . '-headline-font';
    const HEADLINE_BORDER = self::SECTION . '-headline-border';
    const HEADLINE_BOX_SHADOW = self::SECTION . '-headline-box-shadow';
    const SETTING = RCB_OPT_PREFIX . '-banner-design';
    const SETTING_COLOR_BG = self::SETTING . '-bg';
    const SETTING_BORDER_COLOR = self::SETTING . '-border-color';
    const SETTING_BORDER_WIDTH = self::SETTING . '-border-width';
    const SETTING_TEXT_ALIGN = self::SETTING . '-text-align';
    const SETTING_LINK_TEXT_DECORATION = self::SETTING . '-link-text-decoration';
    const SETTING_FONT_SIZE = self::SETTING . '-font-size';
    const SETTING_FONT_COLOR = self::SETTING . '-font-color';
    const SETTING_FONT_INHERIT_FAMILY = self::SETTING . '-font-inherit-family';
    const SETTING_FONT_FAMILY = self::SETTING . '-font-family';
    const SETTING_FONT_WEIGHT = self::SETTING . '-font-weight';
    const SETTING_BOX_SHADOW_ENABLED = self::SETTING . '-box-shadow-enabled';
    const SETTING_BOX_SHADOW_OFFSET_X = self::SETTING . '-box-shadow-offset-x';
    const SETTING_BOX_SHADOW_OFFSET_Y = self::SETTING . '-box-shadow-offset-y';
    const SETTING_BOX_SHADOW_BLUR_RADIUS = self::SETTING . '-box-shadow-blur-radius';
    const SETTING_BOX_SHADOW_SPREAD_RADIUS = self::SETTING . '-box-shadow-spread-radius';
    const SETTING_BOX_SHADOW_COLOR = self::SETTING . '-box-shadow-color';
    const SETTING_BOX_SHADOW_COLOR_ALPHA = self::SETTING . '-box-shadow-color-alpha';
    const DEFAULT_COLOR_BG = '#ffffff';
    const DEFAULT_BORDER_COLOR = '#ffffff';
    const DEFAULT_BORDER_WIDTH = 0;
    const DEFAULT_TEXT_ALIGN = 'center';
    const DEFAULT_LINK_TEXT_DECORATION = 'underline';
    const DEFAULT_FONT_SIZE = 13;
    const DEFAULT_FONT_COLOR = '#2b2b2b';
    const DEFAULT_FONT_INHERIT_FAMILY = \true;
    const DEFAULT_FONT_FAMILY = 'Arial, Helvetica, sans-serif';
    const DEFAULT_FONT_WEIGHT = 'normal';
    const DEFAULT_BOX_SHADOW_ENABLED = \true;
    const DEFAULT_BOX_SHADOW_OFFSET_X = 0;
    const DEFAULT_BOX_SHADOW_OFFSET_Y = 5;
    const DEFAULT_BOX_SHADOW_BLUR_RADIUS = 13;
    const DEFAULT_BOX_SHADOW_SPREAD_RADIUS = 0;
    const DEFAULT_BOX_SHADOW_COLOR = '#000000';
    const DEFAULT_BOX_SHADOW_COLOR_ALPHA = 20;
    /**
     * Return arguments for this section.
     */
    public function args()
    {
        return ['name' => 'design', 'title' => \__('General', RCB_TD), 'controls' => [self::SETTING_COLOR_BG => ['name' => 'bg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color', RCB_TD), 'setting' => ['default' => self::DEFAULT_COLOR_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_TEXT_ALIGN => ['name' => 'textAlign', 'label' => \__('Text align', RCB_TD), 'type' => 'select', 'choices' => \DevOwl\RealCookieBanner\view\customize\banner\BodyDesign::getTextAlignChoices(), 'setting' => ['default' => self::DEFAULT_TEXT_ALIGN]], self::SETTING_LINK_TEXT_DECORATION => ['name' => 'linkTextDecoration', 'label' => \__('Link text decoration', RCB_TD), 'type' => 'select', 'choices' => ['none' => \__('None', RCB_TD), 'underline' => \__('Underline', RCB_TD)], 'setting' => ['default' => self::DEFAULT_LINK_TEXT_DECORATION]], self::HEADLINE_BORDER => ['class' => Headline::class, 'label' => \__('Border', RCB_TD)], self::SETTING_BORDER_WIDTH => ['name' => 'borderWidth', 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'step' => 1, 'max' => 100], 'label' => \__('Width', RCB_TD), 'setting' => ['default' => self::DEFAULT_BORDER_WIDTH, 'sanitize_callback' => 'absint']], self::SETTING_BORDER_COLOR => ['name' => 'borderColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BORDER_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_FONT => ['class' => Headline::class, 'label' => \__('Font', RCB_TD)], self::SETTING_FONT_SIZE => ['name' => 'fontSize', 'label' => \__('Size', RCB_TD), 'description' => \__('The font size will be applied to the entire dialog. You can overwrite this setting e.g for headings in the respective section.', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 10, 'max' => 30, 'step' => 0], 'setting' => ['default' => self::DEFAULT_FONT_SIZE, 'sanitize_callback' => 'absint']], self::SETTING_FONT_COLOR => ['name' => 'fontColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_FONT_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_FONT_INHERIT_FAMILY => ['name' => 'fontInheritFamily', 'label' => \__('Adopt font family from theme', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_FONT_INHERIT_FAMILY, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_FONT_FAMILY => ['name' => 'fontFamily', 'label' => \__('Family', RCB_TD), 'type' => 'select', 'choices' => \array_combine(AbstractCustomizePanel::WEB_SAFE_FONT_FAMILY, AbstractCustomizePanel::WEB_SAFE_FONT_FAMILY), 'setting' => ['default' => self::DEFAULT_FONT_FAMILY]], self::SETTING_FONT_WEIGHT => ['name' => 'fontWeight', 'label' => \__('Font weight', RCB_TD), 'type' => 'select', 'choices' => \DevOwl\RealCookieBanner\view\customize\banner\BodyDesign::getFontWeightChoices(), 'setting' => ['default' => self::DEFAULT_FONT_WEIGHT]], self::HEADLINE_BOX_SHADOW => ['class' => Headline::class, 'label' => \__('Box shadow settings', RCB_TD)], self::SETTING_BOX_SHADOW_ENABLED => ['name' => 'boxShadowEnabled', 'label' => \__('Enable box shadow', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_BOX_SHADOW_ENABLED, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_BOX_SHADOW_OFFSET_X => ['name' => 'boxShadowOffsetX', 'label' => \__('Horizontal offset', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => -50, 'max' => 50, 'step' => 0], 'setting' => ['default' => self::DEFAULT_BOX_SHADOW_OFFSET_X, 'sanitize_callback' => function ($value) {
            return \intval($value);
        }]], self::SETTING_BOX_SHADOW_OFFSET_Y => ['name' => 'boxShadowOffsetY', 'label' => \__('Vertical offset', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => -50, 'max' => 50, 'step' => 0], 'setting' => ['default' => self::DEFAULT_BOX_SHADOW_OFFSET_Y, 'sanitize_callback' => function ($value) {
            return \intval($value);
        }]], self::SETTING_BOX_SHADOW_BLUR_RADIUS => ['name' => 'boxShadowBlurRadius', 'label' => \__('Blur radius', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'max' => 100, 'step' => 0], 'setting' => ['default' => self::DEFAULT_BOX_SHADOW_BLUR_RADIUS, 'sanitize_callback' => 'absint']], self::SETTING_BOX_SHADOW_SPREAD_RADIUS => ['name' => 'boxShadowSpreadRadius', 'label' => \__('Spread radius', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'max' => 100, 'step' => 0], 'setting' => ['default' => self::DEFAULT_BOX_SHADOW_SPREAD_RADIUS, 'sanitize_callback' => 'absint']], self::SETTING_BOX_SHADOW_COLOR => ['name' => 'boxShadowColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BOX_SHADOW_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_BOX_SHADOW_COLOR_ALPHA => ['name' => 'boxShadowColorAlpha', 'label' => \__('Color opacity', RCB_TD), 'class' => RangeInput::class, 'unit' => '%', 'input_attrs' => ['min' => 0, 'max' => 100, 'step' => 0], 'setting' => ['default' => self::DEFAULT_BOX_SHADOW_COLOR_ALPHA, 'sanitize_callback' => 'absint']]]];
    }
}
