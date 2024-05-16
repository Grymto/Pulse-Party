<?php

namespace DevOwl\RealCookieBanner\view\customize\banner\individual;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\AbstractCustomizePanel;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls\CssMarginInput;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls\Headline;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls\RangeInput;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\view\BannerCustomize;
use DevOwl\RealCookieBanner\view\customize\banner\BodyDesign;
use WP_Customize_Color_Control;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * "Group" in Individual Privacy settings inclusive checkbox.
 * @internal
 */
class Group
{
    use UtilsProvider;
    const SECTION = BannerCustomize::PANEL_MAIN . '-group';
    const HEADLINE_CHECKBOX = self::SECTION . '-headline-checkbox';
    const HEADLINE_CHECKBOX_BORDER = self::SECTION . '-headline-checkbox-border';
    const HEADLINE_CHECKBOX_ACTIVE = self::SECTION . '-headline-checkbox-active';
    const HEADLINE_GROUP = self::SECTION . '-headline-group';
    const HEADLINE_GROUP_BORDER = self::SECTION . '-headline-group-border';
    const HEADLINE_GROUP_HEADLINE = self::SECTION . '-headline-group-headline';
    const HEADLINE_GROUP_DESCRIPTION = self::SECTION . '-hadline-group-desc';
    const HEADLINE_GROUP_LINK = self::SECTION . '-headline-group-link';
    const SETTING = RCB_OPT_PREFIX . '-group';
    const SETTING_CHECKBOX_BG = self::SETTING . '-checkbox-bg';
    const SETTING_CHECKBOX_BORDER_WIDTH = self::SETTING . '-checkbox-border-width';
    const SETTING_CHECKBOX_BORDER_COLOR = self::SETTING . '-checkbox-border-color';
    const SETTING_CHECKBOX_ACTIVE_COLOR = self::SETTING . '-checkbox-active-color';
    const SETTING_CHECKBOX_ACTIVE_BG = self::SETTING . '-checkbox-active-bg';
    const SETTING_CHECKBOX_ACTIVE_BORDER_COLOR = self::SETTING . '-checkbox-active-border-color';
    const SETTING_GROUP_INHERIT_BG = self::SETTING . '-group-inherit-bg';
    const SETTING_GROUP_BG = self::SETTING . '-group-bg';
    const SETTING_GROUP_PADDING = self::SETTING . '-group-padding';
    const SETTING_GROUP_SPACING = self::SETTING . '-group-spacing';
    const SETTING_GROUP_BORDER_RADIUS = self::SETTING . '-group-border-radius';
    const SETTING_GROUP_BORDER_WIDTH = self::SETTING . '-group-border-width';
    const SETTING_GROUP_BORDER_COLOR = self::SETTING . '-group-border-color';
    const SETTING_HEADLINE_FONT_SIZE = self::SETTING . '-headline-font-size';
    const SETTING_HEADLINE_FONT_WEIGHT = self::SETTING . '-headline-font-weight';
    const SETTING_HEADLINE_COLOR = self::SETTING . '-headline-color';
    const SETTING_DESCRIPTION_FONT_SIZE = self::SETTING . '-desc-font-size';
    const SETTING_DESCRIPTION_COLOR = self::SETTING . '-desc-color';
    const SETTING_LINK_COLOR = self::SETTING . '-link-color';
    const SETTING_LINK_HOVER_COLOR = self::SETTING . '-link-hover-color';
    const DEFAULT_CHECKBOX_BG = '#f0f0f0';
    const DEFAULT_CHECKBOX_BORDER_WIDTH = 1;
    const DEFAULT_CHECKBOX_BORDER_COLOR = '#d2d2d2';
    const DEFAULT_CHECKBOX_ACTIVE_COLOR = '#ffffff';
    const DEFAULT_CHECKBOX_ACTIVE_BG = BodyDesign::DEFAULT_DOTTED_GROUPS_BULLET_COLOR;
    const DEFAULT_CHECKBOX_ACTIVE_BORDER_COLOR = BodyDesign::DEFAULT_BUTTON_ACCEPT_ALL_HOVER_BG;
    const DEFAULT_GROUP_INHERIT_BG = \true;
    const DEFAULT_GROUP_BG = '#f4f4f4';
    const DEFAULT_GROUP_PADDING = [15, 15, 15, 15];
    const DEFAULT_GROUP_SPACING = 10;
    const DEFAULT_GROUP_BORDER_RADIUS = 5;
    const DEFAULT_GROUP_BORDER_WIDTH = 1;
    const DEFAULT_GROUP_BORDER_COLOR = '#f4f4f4';
    const DEFAULT_HEADLINE_FONT_SIZE = 16;
    const DEFAULT_HEADLINE_FONT_WEIGHT = 'normal';
    const DEFAULT_HEADLINE_COLOR = '#2b2b2b';
    const DEFAULT_DESCRIPTION_FONT_SIZE = 14;
    const DEFAULT_DESCRIPTION_COLOR = '#757575';
    const DEFAULT_LINK_COLOR = '#757575';
    const DEFAULT_LINK_HOVER_COLOR = '#2b2b2b';
    /**
     * Return arguments for this section.
     */
    public function args()
    {
        return ['name' => 'group', 'title' => \__('Service groups', RCB_TD), 'controls' => [self::HEADLINE_CHECKBOX => ['class' => Headline::class, 'label' => \__('Checkbox', RCB_TD)], self::SETTING_CHECKBOX_BG => ['name' => 'checkboxBg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color', RCB_TD), 'setting' => ['default' => self::DEFAULT_CHECKBOX_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_CHECKBOX_BORDER => ['class' => Headline::class, 'label' => \__('Border', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_CHECKBOX_BORDER_WIDTH => ['name' => 'checkboxBorderWidth', 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'max' => 15, 'step' => 1], 'label' => \__('Border width', RCB_TD), 'setting' => ['default' => self::DEFAULT_CHECKBOX_BORDER_WIDTH, 'sanitize_callback' => 'absint']], self::SETTING_CHECKBOX_BORDER_COLOR => ['name' => 'checkboxBorderColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Border color', RCB_TD), 'setting' => ['default' => self::DEFAULT_CHECKBOX_BORDER_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_CHECKBOX_ACTIVE => ['class' => Headline::class, 'label' => \__('Checked state', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_CHECKBOX_ACTIVE_COLOR => ['name' => 'checkboxActiveColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Check icon color', RCB_TD), 'setting' => ['default' => self::DEFAULT_CHECKBOX_ACTIVE_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_CHECKBOX_ACTIVE_BG => ['name' => 'checkboxActiveBg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color', RCB_TD), 'setting' => ['default' => self::DEFAULT_CHECKBOX_ACTIVE_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_CHECKBOX_ACTIVE_BORDER_COLOR => ['name' => 'checkboxActiveBorderColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Border color', RCB_TD), 'setting' => ['default' => self::DEFAULT_CHECKBOX_ACTIVE_BORDER_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_GROUP => ['class' => Headline::class, 'label' => \__('Group box', RCB_TD)], self::SETTING_GROUP_INHERIT_BG => ['name' => 'groupInheritBg', 'label' => \__('Adopt background color', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_GROUP_INHERIT_BG, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_GROUP_BG => ['name' => 'groupBg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color', RCB_TD), 'setting' => ['default' => self::DEFAULT_GROUP_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_GROUP_PADDING => ['class' => CssMarginInput::class, 'name' => 'groupPadding', 'label' => \__('Padding', RCB_TD), 'description' => \__('Define the inner distance of the group box.', RCB_TD), 'dashicon' => 'editor-contract', 'setting' => ['default' => self::DEFAULT_GROUP_PADDING]], self::SETTING_GROUP_SPACING => ['name' => 'groupSpacing', 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'step' => 1, 'max' => 300], 'label' => \__('Spacing between groups', RCB_TD), 'setting' => ['default' => self::DEFAULT_GROUP_SPACING, 'sanitize_callback' => 'absint']], self::SETTING_GROUP_BORDER_RADIUS => ['name' => 'groupBorderRadius', 'label' => \__('Border radius', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'max' => 50, 'step' => 0], 'setting' => ['default' => self::DEFAULT_GROUP_BORDER_RADIUS, 'sanitize_callback' => 'absint']], self::HEADLINE_GROUP_BORDER => ['class' => Headline::class, 'label' => \__('Border', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_GROUP_BORDER_WIDTH => ['name' => 'groupBorderWidth', 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'max' => 100, 'step' => 1], 'label' => \__('Width', RCB_TD), 'setting' => ['default' => self::DEFAULT_GROUP_BORDER_WIDTH, 'sanitize_callback' => 'absint']], self::SETTING_GROUP_BORDER_COLOR => ['name' => 'groupBorderColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_GROUP_BORDER_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_GROUP_HEADLINE => ['class' => Headline::class, 'label' => \__('Headline', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_HEADLINE_FONT_SIZE => ['name' => 'headlineFontSize', 'label' => \__('Size', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 10, 'max' => 30, 'step' => 0], 'setting' => ['default' => self::DEFAULT_HEADLINE_FONT_SIZE, 'sanitize_callback' => 'absint']], self::SETTING_HEADLINE_FONT_WEIGHT => ['name' => 'headlineFontWeight', 'label' => \__('Font weight', RCB_TD), 'type' => 'select', 'choices' => BodyDesign::getFontWeightChoices(), 'setting' => ['default' => self::DEFAULT_HEADLINE_FONT_WEIGHT]], self::SETTING_HEADLINE_COLOR => ['name' => 'headlineFontColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_HEADLINE_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_GROUP_DESCRIPTION => ['class' => Headline::class, 'label' => \__('Description', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_DESCRIPTION_FONT_SIZE => ['name' => 'descriptionFontSize', 'label' => \__('Size', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 10, 'max' => 30, 'step' => 0], 'setting' => ['default' => self::DEFAULT_DESCRIPTION_FONT_SIZE, 'sanitize_callback' => 'absint']], self::SETTING_DESCRIPTION_COLOR => ['name' => 'descriptionFontColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_DESCRIPTION_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_GROUP_LINK => ['class' => Headline::class, 'label' => \__('Link', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_LINK_COLOR => ['name' => 'linkColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Font color', RCB_TD), 'setting' => ['default' => self::DEFAULT_LINK_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_LINK_HOVER_COLOR => ['name' => 'linkHoverColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Font color on hover', RCB_TD), 'setting' => ['default' => self::DEFAULT_LINK_HOVER_COLOR, 'sanitize_callback' => 'sanitize_hex_color']]]];
    }
}
