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
 * Body design.
 * @internal
 */
class BodyDesign
{
    use UtilsProvider;
    const SECTION = BannerCustomize::PANEL_MAIN . '-body-design';
    const HEADLINE_TEACHINGS = self::SECTION . '-headline-teachings';
    const HEADLINE_TEACHINGS_FONT = self::SECTION . '-headline-teachings-font';
    const HEADLINE_TEACHINGS_SEPARATOR = self::SECTION . '-headline-teachings-separator';
    const HEADLINE_ACCORDION = self::SECTION . '-headline-accordion';
    const HEADLINE_ACCORDION_BORDER = self::SECTION . '-headline-accordion-border';
    const HEADLINE_ACCORDION_TITLE = self::SECTION . '-headline-accordion-title';
    const HEADLINE_ACCORDION_DESCRIPTION = self::SECTION . '-headline-accordion-description';
    const HEADLINE_BUTTON_ACCEPT_ALL = self::SECTION . '-headline-btn-accept-all';
    const HEADLINE_BUTTON_ACCEPT_ALL_FONT = self::SECTION . '-headline-btn-accept-all-font';
    const HEADLINE_BUTTON_ACCEPT_ALL_BORDER = self::SECTION . '-headline-btn-accept-all-border';
    const HEADLINE_BUTTON_ACCEPT_ALL_HOVER = self::SECTION . '-headline-btn-accept-all-hover';
    const HEADLINE_BUTTON_ACCEPT_ESSENTIALS = self::SECTION . '-headline-btn-accept-essentials';
    const HEADLINE_BUTTON_ACCEPT_ESSENTIALS_FONT = self::SECTION . '-headline-btn-accept-essentials-font';
    const HEADLINE_BUTTON_ACCEPT_ESSENTIALS_BORDER = self::SECTION . '-headline-btn-accept-essentials-border';
    const HEADLINE_BUTTON_ACCEPT_ESSENTIALS_HOVER = self::SECTION . '-headline-btn-accept-essentials-hover';
    const HEADLINE_BUTTON_ACCEPT_INDIVIDUAL = self::SECTION . '-headline-btn-accept-individual';
    const HEADLINE_BUTTON_ACCEPT_INDIVIDUAL_FONT = self::SECTION . '-headline-btn-accept-individual-font';
    const HEADLINE_BUTTON_ACCEPT_INDIVIDUAL_BORDER = self::SECTION . '-headline-btn-accept-individual-border';
    const HEADLINE_BUTTON_ACCEPT_INDIVIDUAL_HOVER = self::SECTION . '-headline-btn-accept-individual-hover';
    const SETTING = RCB_OPT_PREFIX . '-banner-body-design';
    const SETTING_PADDING = self::SETTING . '-padding';
    const SETTING_DESCRIPTION_INHERIT_FONT_SIZE = self::SETTING . '-desc-inherit-font-size';
    const SETTING_DESCRIPTION_FONT_SIZE = self::SETTING . '-desc-font-size';
    const SETTING_DOTTED_GROUPS_INHERIT_FONT_SIZE = self::SETTING . '-dotted-groups-inherit-font-size';
    const SETTING_DOTTED_GROUPS_FONT_SIZE = self::SETTING . '-dotted-groups-font-size';
    const SETTING_DOTTED_GROUPS_BULLET_COLOR = self::SETTING . '-dotted-groups-bullet-color';
    const SETTING_TEACHINGS_INHERIT_FONT_SIZE = self::SETTING . '-teachings-inherit-font-size';
    const SETTING_TEACHINGS_FONT_SIZE = self::SETTING . '-teachings-font-size';
    const SETTING_TEACHINGS_INHERIT_FONT_COLOR = self::SETTING . '-teachings-inherit-font-color';
    const SETTING_TEACHINGS_FONT_COLOR = self::SETTING . '-teachings-font-color';
    const SETTING_TEACHINGS_INHERIT_TEXT_ALIGN = self::SETTING . '-teachings-inherit-text-align';
    const SETTING_TEACHINGS_TEXT_ALIGN = self::SETTING . '-teachings-text-align';
    const SETTING_TEACHINGS_SEPARATOR_ACTIVE = self::SETTING . '-teachings-separator';
    const SETTING_TEACHINGS_SEPARATOR_WIDTH = self::SETTING . '-teachings-separator-width';
    const SETTING_TEACHINGS_SEPARATOR_HEIGHT = self::SETTING . '-teachings-separator-height';
    const SETTING_TEACHINGS_SEPARATOR_COLOR = self::SETTING . '-teachings-separator-color';
    const SETTING_ACCORDION_MARGIN = self::SETTING . '-accordion-margin';
    const SETTING_ACCORDION_PADDING = self::SETTING . '-accordion-padding';
    const SETTING_ACCORDION_ARROW_TYPE = self::SETTING . '-accordion-arrow-type';
    const SETTING_ACCORDION_ARROW_COLOR = self::SETTING . '-accordion-arrow-color';
    const SETTING_ACCORDION_BG = self::SETTING . '-accordion-bg';
    const SETTING_ACCORDION_ACTIVE_BG = self::SETTING . '-accordion-active-bg';
    const SETTING_ACCORDION_HOVER_BG = self::SETTING . '-accordion-hover-bg';
    const SETTING_ACCORDION_BORDER_WIDTH = self::SETTING . '-accordion-border-width';
    const SETTING_ACCORDION_BORDER_COLOR = self::SETTING . '-accordion-border-color';
    const SETTING_ACCORDION_TITLE_FONT_SIZE = self::SETTING . '-accordion-title-font-size';
    const SETTING_ACCORDION_TITLE_FONT_COLOR = self::SETTING . '-accordion-title-font-color';
    const SETTING_ACCORDION_TITLE_FONT_WEIGHT = self::SETTING . '-accordion-title-font-weight';
    const SETTING_ACCORDION_DESCRIPTION_MARGIN = self::SETTING . '-accordion-description-margin';
    const SETTING_ACCORDION_DESCRIPTION_FONT_SIZE = self::SETTING . '-accordion-description-font-size';
    const SETTING_ACCORDION_DESCRIPTION_FONT_COLOR = self::SETTING . '-accordion-description-font-color';
    const SETTING_ACCORDION_DESCRIPTION_FONT_WEIGHT = self::SETTING . '-accordion-description-font-weight';
    const SETTING_BUTTON_ACCEPT_ALL_TYPE = self::SETTING . '-btn-accept-all-type';
    const SETTING_BUTTON_ACCEPT_ALL_ONE_ROW_LAYOUT = self::SETTING . '-btn-accept-all-one-row-layout';
    const SETTING_BUTTON_ACCEPT_ALL_PADDING = self::SETTING . '-btn-accept-all-padding';
    const SETTING_BUTTON_ACCEPT_ALL_BG = self::SETTING . '-btn-accept-all-bg';
    const SETTING_BUTTON_ACCEPT_ALL_TEXT_ALIGN = self::SETTING . '-btn-accept-all-text-align';
    const SETTING_BUTTON_ACCEPT_ALL_FONT_SIZE = self::SETTING . '-btn-accept-all-font-size';
    const SETTING_BUTTON_ACCEPT_ALL_FONT_COLOR = self::SETTING . '-btn-accept-all-font-color';
    const SETTING_BUTTON_ACCEPT_ALL_FONT_WEIGHT = self::SETTING . '-btn-accept-all-font-weight';
    const SETTING_BUTTON_ACCEPT_ALL_BORDER_WIDTH = self::SETTING . '-btn-accept-all-border-width';
    const SETTING_BUTTON_ACCEPT_ALL_BORDER_COLOR = self::SETTING . '-btn-accept-all-border-color';
    const SETTING_BUTTON_ACCEPT_ALL_HOVER_BG = self::SETTING . '-btn-accept-all-hover-bg';
    const SETTING_BUTTON_ACCEPT_ALL_HOVER_BORDER_COLOR = self::SETTING . '-btn-accept-all-hover-border-color';
    const SETTING_BUTTON_ACCEPT_ALL_HOVER_FONT_COLOR = self::SETTING . '-btn-accept-all-hover-font-color';
    const SETTING_BUTTON_ACCEPT_ESSENTIALS_USE_ACCEPT_ALL = self::SETTING . '-btn-accept-essentials-use-accept-all';
    const SETTING_BUTTON_ACCEPT_ESSENTIALS_TYPE = self::SETTING . '-btn-accept-essentials-type';
    const SETTING_BUTTON_ACCEPT_ESSENTIALS_PADDING = self::SETTING . '-btn-accept-essentials-padding';
    const SETTING_BUTTON_ACCEPT_ESSENTIALS_BG = self::SETTING . '-btn-accept-essentials-bg';
    const SETTING_BUTTON_ACCEPT_ESSENTIALS_TEXT_ALIGN = self::SETTING . '-btn-accept-essentials-text-align';
    const SETTING_BUTTON_ACCEPT_ESSENTIALS_FONT_SIZE = self::SETTING . '-btn-accept-essentials-font-size';
    const SETTING_BUTTON_ACCEPT_ESSENTIALS_FONT_COLOR = self::SETTING . '-btn-accept-essentials-font-color';
    const SETTING_BUTTON_ACCEPT_ESSENTIALS_FONT_WEIGHT = self::SETTING . '-btn-accept-essentials-font-weight';
    const SETTING_BUTTON_ACCEPT_ESSENTIALS_BORDER_WIDTH = self::SETTING . '-btn-accept-essentials-border-width';
    const SETTING_BUTTON_ACCEPT_ESSENTIALS_BORDER_COLOR = self::SETTING . '-btn-accept-essentials-border-color';
    const SETTING_BUTTON_ACCEPT_ESSENTIALS_HOVER_BG = self::SETTING . '-btn-accept-essentials-hover-bg';
    const SETTING_BUTTON_ACCEPT_ESSENTIALS_HOVER_BORDER_COLOR = self::SETTING . '-btn-accept-essentials-hover-border-color';
    const SETTING_BUTTON_ACCEPT_ESSENTIALS_HOVER_FONT_COLOR = self::SETTING . '-btn-accept-essentials-hover-font-color';
    const SETTING_BUTTON_ACCEPT_INDIVIDUAL_TYPE = self::SETTING . '-btn-accept-individual-type';
    const SETTING_BUTTON_ACCEPT_INDIVIDUAL_PADDING = self::SETTING . '-btn-accept-individual-padding';
    const SETTING_BUTTON_ACCEPT_INDIVIDUAL_BG = self::SETTING . '-btn-accept-individual-bg';
    const SETTING_BUTTON_ACCEPT_INDIVIDUAL_TEXT_ALIGN = self::SETTING . '-btn-accept-individual-text-align';
    const SETTING_BUTTON_ACCEPT_INDIVIDUAL_FONT_SIZE = self::SETTING . '-btn-accept-individual-font-size';
    const SETTING_BUTTON_ACCEPT_INDIVIDUAL_FONT_COLOR = self::SETTING . '-btn-accept-individual-font-color';
    const SETTING_BUTTON_ACCEPT_INDIVIDUAL_FONT_WEIGHT = self::SETTING . '-btn-accept-individual-font-weight';
    const SETTING_BUTTON_ACCEPT_INDIVIDUAL_BORDER_WIDTH = self::SETTING . '-btn-accept-individual-border-width';
    const SETTING_BUTTON_ACCEPT_INDIVIDUAL_BORDER_COLOR = self::SETTING . '-btn-accept-individual-border-color';
    const SETTING_BUTTON_ACCEPT_INDIVIDUAL_HOVER_BG = self::SETTING . '-btn-accept-individual-hover-bg';
    const SETTING_BUTTON_ACCEPT_INDIVIDUAL_HOVER_BORDER_COLOR = self::SETTING . '-btn-accept-individual-hover-border-color';
    const SETTING_BUTTON_ACCEPT_INDIVIDUAL_HOVER_FONT_COLOR = self::SETTING . '-btn-accept-individual-hover-font-color';
    const DEFAULT_PADDING = [15, 20, 5, 20];
    const DEFAULT_DESCRIPTION_INHERIT_FONT_SIZE = \true;
    const DEFAULT_DESCRIPTION_FONT_SIZE = \DevOwl\RealCookieBanner\view\customize\banner\Design::DEFAULT_FONT_SIZE;
    const DEFAULT_DOTTED_GROUPS_INHERIT_FONT_SIZE = \true;
    const DEFAULT_DOTTED_GROUPS_FONT_SIZE = \DevOwl\RealCookieBanner\view\customize\banner\Design::DEFAULT_FONT_SIZE;
    const DEFAULT_DOTTED_GROUPS_BULLET_COLOR = '#15779b';
    const DEFAULT_TEACHINGS_INHERIT_FONT_SIZE = \false;
    const DEFAULT_TEACHINGS_FONT_SIZE = \DevOwl\RealCookieBanner\view\customize\banner\Design::DEFAULT_FONT_SIZE - 1;
    const DEFAULT_TEACHINGS_INHERIT_FONT_COLOR = \false;
    const DEFAULT_TEACHINGS_FONT_COLOR = '#757575';
    const DEFAULT_TEACHINGS_INHERIT_TEXT_ALIGN = \true;
    const DEFAULT_TEACHINGS_TEXT_ALIGN = \DevOwl\RealCookieBanner\view\customize\banner\Design::DEFAULT_TEXT_ALIGN;
    const DEFAULT_TEACHINGS_SEPARATOR_ACTIVE = \true;
    const DEFAULT_TEACHINGS_SEPARATOR_WIDTH = 50;
    const DEFAULT_TEACHINGS_SEPARATOR_HEIGHT = 1;
    const DEFAULT_TEACHINGS_SEPARATOR_COLOR = self::DEFAULT_DOTTED_GROUPS_BULLET_COLOR;
    const DEFAULT_ACCORDION_MARGIN = [10, 0, 5, 0];
    const DEFAULT_ACCORDION_PADDING = [5, 10, 5, 10];
    const DEFAULT_ACCORDION_ARROW_TYPE = 'outlined';
    const DEFAULT_ACCORDION_ARROW_COLOR = self::DEFAULT_DOTTED_GROUPS_BULLET_COLOR;
    const DEFAULT_ACCORDION_BG = \DevOwl\RealCookieBanner\view\customize\banner\Design::DEFAULT_COLOR_BG;
    const DEFAULT_ACCORDION_ACTIVE_BG = '#f9f9f9';
    const DEFAULT_ACCORDION_HOVER_BG = self::DEFAULT_ACCORDION_BORDER_COLOR;
    const DEFAULT_ACCORDION_TITLE_FONT_SIZE = self::DEFAULT_TEACHINGS_FONT_SIZE;
    const DEFAULT_ACCORDION_TITLE_FONT_COLOR = \DevOwl\RealCookieBanner\view\customize\banner\Design::DEFAULT_FONT_COLOR;
    const DEFAULT_ACCORDION_TITLE_FONT_WEIGHT = \DevOwl\RealCookieBanner\view\customize\banner\Design::DEFAULT_FONT_WEIGHT;
    const DEFAULT_ACCORDION_DESCRIPTION_MARGIN = [5, 0, 0, 0];
    const DEFAULT_ACCORDION_DESCRIPTION_FONT_SIZE = self::DEFAULT_ACCORDION_TITLE_FONT_SIZE;
    const DEFAULT_ACCORDION_DESCRIPTION_FONT_COLOR = '#757575';
    const DEFAULT_ACCORDION_DESCRIPTION_FONT_WEIGHT = self::DEFAULT_ACCORDION_TITLE_FONT_WEIGHT;
    const DEFAULT_ACCORDION_BORDER_WIDTH = 1;
    const DEFAULT_ACCORDION_BORDER_COLOR = \DevOwl\RealCookieBanner\view\customize\banner\HeaderDesign::DEFAULT_BOTTOM_BORDER_COLOR;
    const DEFAULT_BUTTON_ACCEPT_ALL_ONE_ROW_LAYOUT = \false;
    const DEFAULT_BUTTON_ACCEPT_ALL_PADDING = [10, 10, 10, 10];
    const DEFAULT_BUTTON_ACCEPT_ALL_BG = self::DEFAULT_DOTTED_GROUPS_BULLET_COLOR;
    const DEFAULT_BUTTON_ACCEPT_ALL_TEXT_ALIGN = 'center';
    const DEFAULT_BUTTON_ACCEPT_ALL_FONT_SIZE = 18;
    const DEFAULT_BUTTON_ACCEPT_ALL_FONT_COLOR = '#ffffff';
    const DEFAULT_BUTTON_ACCEPT_ALL_FONT_WEIGHT = 'normal';
    const DEFAULT_BUTTON_ACCEPT_ALL_BORDER_WIDTH = 0;
    const DEFAULT_BUTTON_ACCEPT_ALL_BORDER_COLOR = '#000000';
    const DEFAULT_BUTTON_ACCEPT_ALL_HOVER_BG = '#11607d';
    const DEFAULT_BUTTON_ACCEPT_ALL_HOVER_BORDER_COLOR = '#000000';
    const DEFAULT_BUTTON_ACCEPT_ALL_HOVER_FONT_COLOR = '#ffffff';
    const DEFAULT_BUTTON_ACCEPT_ESSENTIALS_USE_ACCEPT_ALL = \true;
    const DEFAULT_BUTTON_ACCEPT_ESSENTIALS_PADDING = self::DEFAULT_BUTTON_ACCEPT_ALL_PADDING;
    const DEFAULT_BUTTON_ACCEPT_ESSENTIALS_BG = '#efefef';
    const DEFAULT_BUTTON_ACCEPT_ESSENTIALS_TEXT_ALIGN = 'center';
    const DEFAULT_BUTTON_ACCEPT_ESSENTIALS_FONT_SIZE = 18;
    const DEFAULT_BUTTON_ACCEPT_ESSENTIALS_FONT_COLOR = '#0a0a0a';
    const DEFAULT_BUTTON_ACCEPT_ESSENTIALS_FONT_WEIGHT = self::DEFAULT_BUTTON_ACCEPT_ALL_FONT_WEIGHT;
    const DEFAULT_BUTTON_ACCEPT_ESSENTIALS_BORDER_WIDTH = 0;
    const DEFAULT_BUTTON_ACCEPT_ESSENTIALS_BORDER_COLOR = '#000000';
    const DEFAULT_BUTTON_ACCEPT_ESSENTIALS_HOVER_BG = '#e8e8e8';
    const DEFAULT_BUTTON_ACCEPT_ESSENTIALS_HOVER_BORDER_COLOR = '#000000';
    const DEFAULT_BUTTON_ACCEPT_ESSENTIALS_HOVER_FONT_COLOR = '#000000';
    const DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_PADDING = [5, 5, 5, 5];
    const DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_BG = '#ffffff';
    const DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_TEXT_ALIGN = 'center';
    const DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_FONT_SIZE = 16;
    const DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_FONT_COLOR = '#15779b';
    const DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_FONT_WEIGHT = self::DEFAULT_BUTTON_ACCEPT_ALL_FONT_WEIGHT;
    const DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_BORDER_WIDTH = 0;
    const DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_BORDER_COLOR = '#000000';
    const DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_HOVER_BG = '#ffffff';
    const DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_HOVER_BORDER_COLOR = '#000000';
    const DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_HOVER_FONT_COLOR = '#11607d';
    /**
     * Return arguments for this section.
     */
    public function args()
    {
        return ['name' => 'bodyDesign', 'title' => \__('Body', RCB_TD), 'controls' => [self::SETTING_PADDING => ['class' => CssMarginInput::class, 'name' => 'padding', 'label' => \__('Padding', RCB_TD), 'description' => \__('Define inner distance of the body.', RCB_TD), 'dashicon' => 'editor-contract', 'setting' => ['default' => self::DEFAULT_PADDING]], self::SETTING_DESCRIPTION_INHERIT_FONT_SIZE => ['name' => 'descriptionInheritFontSize', 'label' => \__('Adopt font size for description block', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_DESCRIPTION_INHERIT_FONT_SIZE, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_DESCRIPTION_FONT_SIZE => ['name' => 'descriptionFontSize', 'label' => \__('Description font size', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 10, 'max' => 30, 'step' => 0], 'setting' => ['default' => self::DEFAULT_DESCRIPTION_FONT_SIZE, 'sanitize_callback' => 'absint']], self::SETTING_DOTTED_GROUPS_INHERIT_FONT_SIZE => ['name' => 'dottedGroupsInheritFontSize', 'label' => \__('Adopt font size for service groups', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_DOTTED_GROUPS_INHERIT_FONT_SIZE, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_DOTTED_GROUPS_FONT_SIZE => ['name' => 'dottedGroupsFontSize', 'label' => \__('Service groups font size', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 10, 'max' => 30, 'step' => 0], 'setting' => ['default' => self::DEFAULT_DOTTED_GROUPS_FONT_SIZE, 'sanitize_callback' => 'absint']], self::SETTING_DOTTED_GROUPS_BULLET_COLOR => ['name' => 'dottedGroupsBulletColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Bullet color', RCB_TD), 'setting' => ['default' => self::DEFAULT_DOTTED_GROUPS_BULLET_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_TEACHINGS => ['class' => Headline::class, 'name' => 'bodyDesignTeachings', 'label' => \__('Additional teachings', RCB_TD), 'description' => \__('Texts for data processing in unsafe third country and the age notice.', RCB_TD)], self::SETTING_TEACHINGS_INHERIT_TEXT_ALIGN => ['name' => 'teachingsInheritTextAlign', 'label' => \__('Adopt text align', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_TEACHINGS_INHERIT_TEXT_ALIGN, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_TEACHINGS_TEXT_ALIGN => ['name' => 'teachingsTextAlign', 'label' => \__('Text align', RCB_TD), 'type' => 'select', 'choices' => ['left' => \__('Left', RCB_TD), 'right' => \__('Right', RCB_TD), 'center' => \__('Center', RCB_TD), 'justify' => \__('Justify', RCB_TD)], 'setting' => ['default' => self::DEFAULT_TEACHINGS_TEXT_ALIGN]], self::SETTING_TEACHINGS_SEPARATOR_ACTIVE => ['name' => 'teachingsSeparatorActive', 'label' => \__('Enable visual separator', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_TEACHINGS_SEPARATOR_ACTIVE, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::HEADLINE_TEACHINGS_SEPARATOR => ['class' => Headline::class, 'name' => 'bodyDesignTeachingsSeparator', 'label' => \__('Separator', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_TEACHINGS_SEPARATOR_WIDTH => ['name' => 'teachingsSeparatorWidth', 'label' => \__('Width', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 1, 'max' => 1000, 'step' => 5], 'setting' => ['default' => self::DEFAULT_TEACHINGS_SEPARATOR_WIDTH, 'sanitize_callback' => 'absint']], self::SETTING_TEACHINGS_SEPARATOR_HEIGHT => ['name' => 'teachingsSeparatorHeight', 'label' => \__('Height', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 1, 'max' => 20, 'step' => 1], 'setting' => ['default' => self::DEFAULT_TEACHINGS_SEPARATOR_HEIGHT, 'sanitize_callback' => 'absint']], self::SETTING_TEACHINGS_SEPARATOR_COLOR => ['name' => 'teachingsSeparatorColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_TEACHINGS_SEPARATOR_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_TEACHINGS_FONT => ['class' => Headline::class, 'name' => 'bodyDesignTeachingsFont', 'label' => \__('Font', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_TEACHINGS_INHERIT_FONT_SIZE => ['name' => 'teachingsInheritFontSize', 'label' => \__('Adopt font size', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_TEACHINGS_INHERIT_FONT_SIZE, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_TEACHINGS_FONT_SIZE => ['name' => 'teachingsFontSize', 'label' => \__('Size', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 10, 'max' => 30, 'step' => 0], 'setting' => ['default' => self::DEFAULT_TEACHINGS_FONT_SIZE, 'sanitize_callback' => 'absint']], self::SETTING_TEACHINGS_INHERIT_FONT_COLOR => ['name' => 'teachingsInheritFontColor', 'label' => \__('Adopt font color', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_TEACHINGS_INHERIT_FONT_COLOR, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_TEACHINGS_FONT_COLOR => ['name' => 'teachingsFontColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_TEACHINGS_FONT_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_ACCORDION => ['class' => Headline::class, 'name' => 'bodyDesignAccordion', 'label' => \__('Accordions and list', RCB_TD)], self::SETTING_ACCORDION_MARGIN => ['class' => CssMarginInput::class, 'name' => 'accordionMargin', 'label' => \__('Margin', RCB_TD), 'description' => \__('Define outer distance of the container.', RCB_TD), 'setting' => ['default' => self::DEFAULT_ACCORDION_MARGIN]], self::SETTING_ACCORDION_PADDING => ['class' => CssMarginInput::class, 'name' => 'accordionPadding', 'label' => \__('Padding', RCB_TD), 'description' => \__('Define inner distance of one stack.', RCB_TD), 'dashicon' => 'editor-contract', 'setting' => ['default' => self::DEFAULT_ACCORDION_PADDING]], self::SETTING_ACCORDION_ARROW_TYPE => ['name' => 'accordionArrowType', 'label' => \__('Arrow type', RCB_TD), 'type' => 'select', 'choices' => ['none' => \__('None', RCB_TD), 'filled' => \__('Filled', RCB_TD), 'outlined' => \__('Outlined', RCB_TD)], 'setting' => ['default' => self::DEFAULT_ACCORDION_ARROW_TYPE]], self::SETTING_ACCORDION_ARROW_COLOR => ['name' => 'accordionArrowColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Arrow color', RCB_TD), 'setting' => ['default' => self::DEFAULT_ACCORDION_ARROW_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_ACCORDION_BG => ['name' => 'accordionBg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color', RCB_TD), 'setting' => ['default' => self::DEFAULT_ACCORDION_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_ACCORDION_ACTIVE_BG => ['name' => 'accordionActiveBg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color when active', RCB_TD), 'setting' => ['default' => self::DEFAULT_ACCORDION_ACTIVE_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_ACCORDION_HOVER_BG => ['name' => 'accordionHoverBg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color on hover', RCB_TD), 'setting' => ['default' => self::DEFAULT_ACCORDION_HOVER_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_ACCORDION_BORDER => ['class' => Headline::class, 'name' => 'bodyDesignAccordionBorder', 'label' => \__('Border', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_ACCORDION_BORDER_WIDTH => ['name' => 'accordionBorderWidth', 'type' => 'number', 'input_attrs' => ['min' => 0], 'label' => \__('Border width (px)', RCB_TD), 'setting' => ['default' => self::DEFAULT_ACCORDION_BORDER_WIDTH, 'sanitize_callback' => 'absint']], self::SETTING_ACCORDION_BORDER_COLOR => ['name' => 'accordionBorderColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_ACCORDION_BORDER_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_ACCORDION_TITLE => ['class' => Headline::class, 'name' => 'bodyDesignAccordionTitle', 'label' => \__('Title', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_ACCORDION_TITLE_FONT_SIZE => ['name' => 'accordionTitleFontSize', 'label' => \__('Size', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 10, 'max' => 30, 'step' => 0], 'setting' => ['default' => self::DEFAULT_ACCORDION_TITLE_FONT_SIZE, 'sanitize_callback' => 'absint']], self::SETTING_ACCORDION_TITLE_FONT_COLOR => ['name' => 'accordionTitleFontColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_ACCORDION_TITLE_FONT_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_ACCORDION_TITLE_FONT_WEIGHT => ['name' => 'accordionTitleFontWeight', 'label' => \__('Font weight', RCB_TD), 'type' => 'select', 'choices' => \DevOwl\RealCookieBanner\view\customize\banner\BodyDesign::getFontWeightChoices(), 'setting' => ['default' => self::DEFAULT_ACCORDION_TITLE_FONT_WEIGHT]], self::HEADLINE_ACCORDION_DESCRIPTION => ['class' => Headline::class, 'name' => 'bodyDesignAccordionDescription', 'label' => \__('Description', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_ACCORDION_DESCRIPTION_MARGIN => [
            'class' => CssMarginInput::class,
            'name' => 'accordionDescriptionMargin',
            'label' => \__('Padding', RCB_TD),
            // for the user it is more like a padding
            'description' => \__('Define inner distance of the description paragraph.', RCB_TD),
            'setting' => ['default' => self::DEFAULT_ACCORDION_DESCRIPTION_MARGIN],
        ], self::SETTING_ACCORDION_DESCRIPTION_FONT_SIZE => ['name' => 'accordionDescriptionFontSize', 'label' => \__('Size', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 10, 'max' => 30, 'step' => 0], 'setting' => ['default' => self::DEFAULT_ACCORDION_DESCRIPTION_FONT_SIZE, 'sanitize_callback' => 'absint']], self::SETTING_ACCORDION_DESCRIPTION_FONT_COLOR => ['name' => 'accordionDescriptionFontColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_ACCORDION_DESCRIPTION_FONT_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_ACCORDION_DESCRIPTION_FONT_WEIGHT => ['name' => 'accordionDescriptionFontWeight', 'label' => \__('Font weight', RCB_TD), 'type' => 'select', 'choices' => \DevOwl\RealCookieBanner\view\customize\banner\BodyDesign::getFontWeightChoices(), 'setting' => ['default' => self::DEFAULT_ACCORDION_DESCRIPTION_FONT_WEIGHT]], self::HEADLINE_BUTTON_ACCEPT_ALL => ['class' => Headline::class, 'name' => 'bodyDesignAcceptAll', 'label' => \__('Button: Accept all', RCB_TD)], self::SETTING_BUTTON_ACCEPT_ALL_TYPE => ['label' => \__('Type', RCB_TD), 'type' => 'acceptAllButtonType'], self::SETTING_BUTTON_ACCEPT_ALL_ONE_ROW_LAYOUT => ['name' => 'acceptAllOneRowLayout', 'label' => \__('Align buttons side by side', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ALL_ONE_ROW_LAYOUT, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_BUTTON_ACCEPT_ALL_PADDING => ['class' => CssMarginInput::class, 'name' => 'acceptAllPadding', 'label' => \__('Padding', RCB_TD), 'description' => \__('Define inner distance of the button/link.', RCB_TD), 'dashicon' => 'editor-contract', 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ALL_PADDING]], self::SETTING_BUTTON_ACCEPT_ALL_BG => ['name' => 'acceptAllBg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ALL_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_BUTTON_ACCEPT_ALL_TEXT_ALIGN => ['name' => 'acceptAllTextAlign', 'label' => \__('Text align', RCB_TD), 'type' => 'select', 'choices' => self::getTextAlignChoices(), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ALL_TEXT_ALIGN]], self::HEADLINE_BUTTON_ACCEPT_ALL_FONT => ['class' => Headline::class, 'name' => 'bodyDesignAcceptAllFont', 'label' => \__('Font', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_BUTTON_ACCEPT_ALL_FONT_SIZE => ['name' => 'acceptAllFontSize', 'label' => \__('Size', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 10, 'max' => 30, 'step' => 0], 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ALL_FONT_SIZE, 'sanitize_callback' => 'absint']], self::SETTING_BUTTON_ACCEPT_ALL_FONT_COLOR => ['name' => 'acceptAllFontColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ALL_FONT_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_BUTTON_ACCEPT_ALL_FONT_WEIGHT => ['name' => 'acceptAllFontWeight', 'label' => \__('Font weight', RCB_TD), 'type' => 'select', 'choices' => \DevOwl\RealCookieBanner\view\customize\banner\BodyDesign::getFontWeightChoices(), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ALL_FONT_WEIGHT]], self::HEADLINE_BUTTON_ACCEPT_ALL_BORDER => ['class' => Headline::class, 'name' => 'bodyDesignAcceptAllBorder', 'label' => \__('Border', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_BUTTON_ACCEPT_ALL_BORDER_WIDTH => ['name' => 'acceptAllBorderWidth', 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'step' => 1, 'max' => 100], 'label' => \__('Border width', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ALL_BORDER_WIDTH, 'sanitize_callback' => 'absint']], self::SETTING_BUTTON_ACCEPT_ALL_BORDER_COLOR => ['name' => 'acceptAllBorderColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ALL_BORDER_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_BUTTON_ACCEPT_ALL_HOVER => ['class' => Headline::class, 'name' => 'bodyDesignAcceptAllHover', 'label' => \__('Transition on hover', RCB_TD), 'level' => 3, 'isSubHeadline' => \true, 'description' => \__('When the user moves the mouse over the button/link, it changes its color.', RCB_TD)], self::SETTING_BUTTON_ACCEPT_ALL_HOVER_BG => ['name' => 'acceptAllHoverBg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ALL_HOVER_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_BUTTON_ACCEPT_ALL_HOVER_FONT_COLOR => ['name' => 'acceptAllHoverFontColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Font color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ALL_HOVER_FONT_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_BUTTON_ACCEPT_ALL_HOVER_BORDER_COLOR => ['name' => 'acceptAllHoverBorderColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Border color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ALL_HOVER_BORDER_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_BUTTON_ACCEPT_ESSENTIALS => ['class' => Headline::class, 'name' => 'bodyDesignAcceptEssentials', 'label' => \__('Button: Continue without consent', RCB_TD)], self::SETTING_BUTTON_ACCEPT_ESSENTIALS_USE_ACCEPT_ALL => ['name' => 'acceptEssentialsUseAcceptAll', 'label' => \__('Use the same stylings as for "Accept all"', RCB_TD), 'description' => \sprintf(
            // translators:
            \__('According to the <a href="%s" target="_blank">guidance of the data protection authorities in Germany (German)</a>, there must be an equivalent way for your visitors to express their choice (agree to everything, continue without consent or an indivudual decision). Equivalent does not necessarily mean that the buttons/links must look exactly the same. However, you should be safest if they look exactly the same.', RCB_TD),
            \__('https://www.datenschutzkonferenz-online.de/media/oh/20211220_oh_telemedien.pdf', RCB_TD)
        ), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ESSENTIALS_USE_ACCEPT_ALL, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_BUTTON_ACCEPT_ESSENTIALS_TYPE => ['label' => \__('Type', RCB_TD), 'type' => 'acceptEssentialsButtonType', 'name' => 'acceptEssentialsButtonType'], self::SETTING_BUTTON_ACCEPT_ESSENTIALS_PADDING => ['class' => CssMarginInput::class, 'name' => 'acceptEssentialsPadding', 'label' => \__('Padding', RCB_TD), 'description' => \__('Define inner distance of the button/link.', RCB_TD), 'dashicon' => 'editor-contract', 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ESSENTIALS_PADDING]], self::SETTING_BUTTON_ACCEPT_ESSENTIALS_BG => ['name' => 'acceptEssentialsBg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ESSENTIALS_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_BUTTON_ACCEPT_ESSENTIALS_TEXT_ALIGN => ['name' => 'acceptEssentialsTextAlign', 'label' => \__('Text align', RCB_TD), 'type' => 'select', 'choices' => self::getTextAlignChoices(), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ESSENTIALS_TEXT_ALIGN]], self::HEADLINE_BUTTON_ACCEPT_ESSENTIALS_FONT => ['class' => Headline::class, 'name' => 'bodyDesignAcceptEssentialsFont', 'label' => \__('Font', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_BUTTON_ACCEPT_ESSENTIALS_FONT_SIZE => ['name' => 'acceptEssentialsFontSize', 'label' => \__('Size', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 10, 'max' => 30, 'step' => 0], 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ESSENTIALS_FONT_SIZE, 'sanitize_callback' => 'absint']], self::SETTING_BUTTON_ACCEPT_ESSENTIALS_FONT_COLOR => ['name' => 'acceptEssentialsFontColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ESSENTIALS_FONT_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_BUTTON_ACCEPT_ESSENTIALS_FONT_WEIGHT => ['name' => 'acceptEssentialsFontWeight', 'label' => \__('Font weight', RCB_TD), 'type' => 'select', 'choices' => \DevOwl\RealCookieBanner\view\customize\banner\BodyDesign::getFontWeightChoices(), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ESSENTIALS_FONT_WEIGHT]], self::HEADLINE_BUTTON_ACCEPT_ESSENTIALS_BORDER => ['class' => Headline::class, 'name' => 'bodyDesignAcceptEssentialsBorder', 'label' => \__('Border', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_BUTTON_ACCEPT_ESSENTIALS_BORDER_WIDTH => ['name' => 'acceptEssentialsBorderWidth', 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'step' => 1, 'max' => 100], 'label' => \__('Border width', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ESSENTIALS_BORDER_WIDTH, 'sanitize_callback' => 'absint']], self::SETTING_BUTTON_ACCEPT_ESSENTIALS_BORDER_COLOR => ['name' => 'acceptEssentialsBorderColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ESSENTIALS_BORDER_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_BUTTON_ACCEPT_ESSENTIALS_HOVER => ['class' => Headline::class, 'name' => 'bodyDesignAcceptEssentialsHover', 'label' => \__('Transition on hover', RCB_TD), 'level' => 3, 'isSubHeadline' => \true, 'description' => \__('When the user moves the mouse over the button/link, it changes its color.', RCB_TD)], self::SETTING_BUTTON_ACCEPT_ESSENTIALS_HOVER_BG => ['name' => 'acceptEssentialsHoverBg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ESSENTIALS_HOVER_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_BUTTON_ACCEPT_ESSENTIALS_HOVER_FONT_COLOR => ['name' => 'acceptEssentialsHoverFontColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Font color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ESSENTIALS_HOVER_FONT_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_BUTTON_ACCEPT_ESSENTIALS_HOVER_BORDER_COLOR => ['name' => 'acceptEssentialsHoverBorderColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Border color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_ESSENTIALS_HOVER_BORDER_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_BUTTON_ACCEPT_INDIVIDUAL => ['class' => Headline::class, 'name' => 'bodyDesignAcceptIndividual', 'label' => \__('Button: Individual privacy preferences', RCB_TD)], self::SETTING_BUTTON_ACCEPT_INDIVIDUAL_TYPE => ['label' => \__('Type', RCB_TD), 'type' => 'acceptIndividualButtonType'], self::SETTING_BUTTON_ACCEPT_INDIVIDUAL_PADDING => ['class' => CssMarginInput::class, 'name' => 'acceptIndividualPadding', 'label' => \__('Padding', RCB_TD), 'description' => \__('Define inner distance of the button/link.', RCB_TD), 'dashicon' => 'editor-contract', 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_PADDING]], self::SETTING_BUTTON_ACCEPT_INDIVIDUAL_BG => ['name' => 'acceptIndividualBg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_BUTTON_ACCEPT_INDIVIDUAL_TEXT_ALIGN => ['name' => 'acceptIndividualTextAlign', 'label' => \__('Text align', RCB_TD), 'type' => 'select', 'choices' => self::getTextAlignChoices(), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_TEXT_ALIGN]], self::HEADLINE_BUTTON_ACCEPT_INDIVIDUAL_FONT => ['class' => Headline::class, 'name' => 'bodyDesignAcceptIndividualFont', 'label' => \__('Font', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_BUTTON_ACCEPT_INDIVIDUAL_FONT_SIZE => ['name' => 'acceptIndividualFontSize', 'label' => \__('Size', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 10, 'max' => 30, 'step' => 0], 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_FONT_SIZE, 'sanitize_callback' => 'absint']], self::SETTING_BUTTON_ACCEPT_INDIVIDUAL_FONT_COLOR => ['name' => 'acceptIndividualFontColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_FONT_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_BUTTON_ACCEPT_INDIVIDUAL_FONT_WEIGHT => ['name' => 'acceptIndividualFontWeight', 'label' => \__('Font weight', RCB_TD), 'type' => 'select', 'choices' => \DevOwl\RealCookieBanner\view\customize\banner\BodyDesign::getFontWeightChoices(), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_FONT_WEIGHT]], self::HEADLINE_BUTTON_ACCEPT_INDIVIDUAL_BORDER => ['class' => Headline::class, 'name' => 'bodyDesignAcceptIndividualBorder', 'label' => \__('Border', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_BUTTON_ACCEPT_INDIVIDUAL_BORDER_WIDTH => ['name' => 'acceptIndividualBorderWidth', 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'max' => 100, 'step' => 1], 'label' => \__('Border width', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_BORDER_WIDTH, 'sanitize_callback' => 'absint']], self::SETTING_BUTTON_ACCEPT_INDIVIDUAL_BORDER_COLOR => ['name' => 'acceptIndividualBorderColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_BORDER_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_BUTTON_ACCEPT_INDIVIDUAL_HOVER => ['class' => Headline::class, 'name' => 'bodyDesignAcceptIndividualHover', 'label' => \__('Transition on hover', RCB_TD), 'level' => 3, 'isSubHeadline' => \true, 'description' => \__('When the user moves the mouse over the button/link, it changes its color.', RCB_TD)], self::SETTING_BUTTON_ACCEPT_INDIVIDUAL_HOVER_BG => ['name' => 'acceptIndividualHoverBg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_HOVER_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_BUTTON_ACCEPT_INDIVIDUAL_HOVER_FONT_COLOR => ['name' => 'acceptIndividualHoverFontColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Font color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_HOVER_FONT_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_BUTTON_ACCEPT_INDIVIDUAL_HOVER_BORDER_COLOR => ['name' => 'acceptIndividualHoverBorderColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Border color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BUTTON_ACCEPT_INDIVIDUAL_HOVER_BORDER_COLOR, 'sanitize_callback' => 'sanitize_hex_color']]]];
    }
    /**
     * Get all available text align choices.
     */
    public static function getTextAlignChoices()
    {
        return ['left' => \__('Left', RCB_TD), 'right' => \__('Right', RCB_TD), 'center' => \__('Center', RCB_TD), 'justify' => \__('Justify', RCB_TD)];
    }
    /**
     * Get all available font weight choices.
     */
    public static function getFontWeightChoices()
    {
        return ['lighter' => \__('Lighter', RCB_TD), 'normal' => \__('Normal', RCB_TD), 'bolder' => \__('Bolder', RCB_TD), 'bold' => \__('Bold', RCB_TD)];
    }
}
