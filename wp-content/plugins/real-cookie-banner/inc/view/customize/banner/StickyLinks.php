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
 * Sticky legal links customization.
 * @internal
 */
class StickyLinks
{
    use UtilsProvider;
    const SECTION = BannerCustomize::PANEL_MAIN . '-sticky-links';
    const HEADLINE_ICON = self::SECTION . '-headline-icon';
    const HEADLINE_BORDER = self::SECTION . '-headline-border';
    const HEADLINE_BOX_SHADOW = self::SECTION . '-headline-box-shadow';
    const HEADLINE_HOVER = self::SECTION . '-headline-hover';
    const HEADLINE_MENU = self::SECTION . '-headline-menu';
    const SETTING = RCB_OPT_PREFIX . '-banner-sticky-links';
    const SETTING_ENABLED = self::SETTING . '-enabled';
    const SETTING_ANIMATIONS_ENABLED = self::SETTING . '-animations-enabled';
    const SETTING_ALIGNMENT = self::SETTING . '-alignment';
    const SETTING_BORDER_RADIUS = self::SETTING . '-border-radius';
    const SETTING_MARGIN = self::SETTING . '-margin';
    const SETTING_PADDING = self::SETTING . '-padding';
    const SETTING_BG = self::SETTING . '-bg';
    const SETTING_BORDER_WIDTH = self::SETTING . '-border-width';
    const SETTING_BORDER_COLOR = self::SETTING . '-border-color';
    const SETTING_ICON = self::SETTING . '-icon';
    const SETTING_ICON_CUSTOM = self::SETTING . '-icon-custom';
    const SETTING_ICON_CUSTOM_RETINA = self::SETTING . '-icon-custom-retina';
    const SETTING_ICON_SIZE = self::SETTING . '-icon-size';
    const SETTING_ICON_COLOR = self::SETTING . '-icon-color';
    const SETTING_BOX_SHADOW_ENABLED = self::SETTING . '-box-shadow-enabled';
    const SETTING_BOX_SHADOW_OFFSET_X = self::SETTING . '-box-shadow-offset-x';
    const SETTING_BOX_SHADOW_OFFSET_Y = self::SETTING . '-box-shadow-offset-y';
    const SETTING_BOX_SHADOW_BLUR_RADIUS = self::SETTING . '-box-shadow-blur-radius';
    const SETTING_BOX_SHADOW_SPREAD_RADIUS = self::SETTING . '-box-shadow-spread-radius';
    const SETTING_BOX_SHADOW_COLOR = self::SETTING . '-box-shadow-color';
    const SETTING_BOX_SHADOW_COLOR_ALPHA = self::SETTING . '-box-shadow-color-alpha';
    const SETTING_HOVER_BG = self::SETTING . '-hover-bg';
    const SETTING_HOVER_BORDER_COLOR = self::SETTING . '-hover-border-color';
    const SETTING_HOVER_ICON_COLOR = self::SETTING . '-hover-icon-color';
    const SETTING_HOVER_ICON_CUSTOM = self::SETTING . '-hover-icon-custom';
    const SETTING_HOVER_ICON_CUSTOM_RETINA = self::SETTING . '-hover-icon-custom-retina';
    const SETTING_MENU_FONT_SIZE = self::SETTING . '-menu-font-size';
    const SETTING_MENU_BORDER_RADIUS = self::SETTING . '-menu-border-radius';
    const SETTING_MENU_ITEM_SPACING = self::SETTING . '-menu-item-spacing';
    const SETTING_MENU_ITEM_PADDING = self::SETTING . '-menu-item-padding';
    const DEFAULT_ENABLED = \false;
    const DEFAULT_ANIMATIONS_ENABLED = \true;
    const DEFAULT_ALIGNMENT = 'left';
    const DEFAULT_BORDER_RADIUS = 50;
    const DEFAULT_MARGIN = [10, 20, 20, 20];
    const DEFAULT_PADDING = 15;
    const DEFAULT_BG = '#15779b';
    const DEFAULT_BORDER_WIDTH = 0;
    const DEFAULT_BORDER_COLOR = '#10556f';
    const DEFAULT_ICON = 'fingerprint';
    const DEFAULT_ICON_SIZE = 30;
    const DEFAULT_ICON_COLOR = '#ffffff';
    const DEFAULT_BOX_SHADOW_ENABLED = \true;
    const DEFAULT_BOX_SHADOW_OFFSET_X = 0;
    const DEFAULT_BOX_SHADOW_OFFSET_Y = 2;
    const DEFAULT_BOX_SHADOW_BLUR_RADIUS = 5;
    const DEFAULT_BOX_SHADOW_SPREAD_RADIUS = 1;
    const DEFAULT_BOX_SHADOW_COLOR = '#105b77';
    const DEFAULT_BOX_SHADOW_COLOR_ALPHA = 40;
    const DEFAULT_HOVER_BG = '#ffffff';
    const DEFAULT_HOVER_BORDER_COLOR = '#000000';
    const DEFAULT_HOVER_ICON_COLOR = '#000000';
    const DEFAULT_MENU_FONT_SIZE = 16;
    const DEFAULT_MENU_BORDER_RADIUS = 5;
    const DEFAULT_MENU_ITEM_SPACING = 10;
    const DEFAULT_MENU_ITEM_PADDING = [5, 10, 5, 10];
    /**
     * Return arguments for this section.
     */
    public function args()
    {
        return ['name' => 'sticky', 'title' => \__('Sticky legal links widget', RCB_TD), 'controls' => [self::SETTING_ENABLED => ['name' => 'enabled', 'type' => 'stickyLinksSwitcher', 'setting' => ['default' => self::DEFAULT_ENABLED, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_ANIMATIONS_ENABLED => ['name' => 'animationsEnabled', 'label' => \__('Enable animations for the menu and menu items', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_ANIMATIONS_ENABLED, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::HEADLINE_ICON => ['class' => Headline::class, 'name' => 'stickyIcon', 'label' => \__('Icon layout', RCB_TD), 'level' => 2], self::SETTING_ALIGNMENT => ['name' => 'alignment', 'label' => \__('Alignment', RCB_TD), 'type' => 'select', 'choices' => ['left' => \__('Left', RCB_TD), 'center' => \__('Center', RCB_TD), 'right' => \__('Right', RCB_TD)], 'setting' => ['default' => self::DEFAULT_ALIGNMENT]], self::SETTING_BORDER_RADIUS => ['name' => 'bubbleBorderRadius', 'label' => \__('Border radius', RCB_TD), 'class' => RangeInput::class, 'unit' => '%', 'input_attrs' => ['min' => 0, 'max' => 50, 'step' => 0], 'setting' => ['default' => self::DEFAULT_BORDER_RADIUS, 'sanitize_callback' => 'absint']], self::SETTING_ICON => ['name' => 'icon', 'label' => \__('Icon', RCB_TD), 'type' => 'select', 'choices' => ['fingerprint' => \__('Fingerprint', RCB_TD), 'padlock' => \__('Padlock', RCB_TD), 'incognito' => \__('Incognito', RCB_TD), 'custom' => \__('Custom icon', RCB_TD)], 'setting' => ['default' => self::DEFAULT_ICON]], self::SETTING_ICON_CUSTOM => ['class' => WP_Customize_Image_Control::class, 'name' => 'iconCustom', 'label' => \__('Custom icon', RCB_TD), 'description' => \__('The width of your logo will be adjusted automatically. We highly recommend to use a square image as the rest of the image will be cut from the icon bubble.', RCB_TD)], self::SETTING_ICON_CUSTOM_RETINA => ['class' => WP_Customize_Image_Control::class, 'name' => 'iconCustomRetina', 'label' => \__('Custom icon (x2 for Retina)', RCB_TD)], self::SETTING_ICON_SIZE => ['name' => 'iconSize', 'label' => \__('Icon size', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'max' => 100, 'step' => 0], 'setting' => ['default' => self::DEFAULT_ICON_SIZE, 'sanitize_callback' => 'absint']], self::SETTING_ICON_COLOR => ['name' => 'iconColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Icon and text color', RCB_TD), 'setting' => ['default' => self::DEFAULT_ICON_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_MARGIN => ['class' => CssMarginInput::class, 'name' => 'bubbleMargin', 'label' => \__('Outer distance', RCB_TD), 'description' => \__('Define the outer distance of the icon. The top-value defines the space between the icon and the menu.', RCB_TD), 'setting' => ['default' => self::DEFAULT_MARGIN]], self::SETTING_PADDING => ['name' => 'bubblePadding', 'label' => \__('Inner distance', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'max' => 100, 'step' => 0], 'setting' => ['default' => self::DEFAULT_PADDING, 'sanitize_callback' => 'absint']], self::SETTING_BG => ['name' => 'bubbleBg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_BORDER => ['class' => Headline::class, 'name' => 'stickyBorder', 'label' => \__('Border', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_BORDER_WIDTH => ['name' => 'bubbleBorderWidth', 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'max' => 100, 'step' => 1], 'label' => \__('Width', RCB_TD), 'setting' => ['default' => self::DEFAULT_BORDER_WIDTH, 'sanitize_callback' => 'absint']], self::SETTING_BORDER_COLOR => ['name' => 'bubbleBorderColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BORDER_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::HEADLINE_BOX_SHADOW => ['class' => Headline::class, 'name' => 'stickyBoxShadow', 'label' => \__('Box shadow settings', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_BOX_SHADOW_ENABLED => ['name' => 'boxShadowEnabled', 'label' => \__('Enable box shadow', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_BOX_SHADOW_ENABLED, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_BOX_SHADOW_OFFSET_X => ['name' => 'boxShadowOffsetX', 'label' => \__('Horizontal offset', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => -50, 'max' => 50, 'step' => 0], 'setting' => ['default' => self::DEFAULT_BOX_SHADOW_OFFSET_X, 'sanitize_callback' => function ($value) {
            return \intval($value);
        }]], self::SETTING_BOX_SHADOW_OFFSET_Y => ['name' => 'boxShadowOffsetY', 'label' => \__('Vertical offset', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => -50, 'max' => 50, 'step' => 0], 'setting' => ['default' => self::DEFAULT_BOX_SHADOW_OFFSET_Y, 'sanitize_callback' => function ($value) {
            return \intval($value);
        }]], self::SETTING_BOX_SHADOW_BLUR_RADIUS => ['name' => 'boxShadowBlurRadius', 'label' => \__('Blur radius', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'max' => 100, 'step' => 0], 'setting' => ['default' => self::DEFAULT_BOX_SHADOW_BLUR_RADIUS, 'sanitize_callback' => 'absint']], self::SETTING_BOX_SHADOW_SPREAD_RADIUS => ['name' => 'boxShadowSpreadRadius', 'label' => \__('Spread radius', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'max' => 100, 'step' => 0], 'setting' => ['default' => self::DEFAULT_BOX_SHADOW_SPREAD_RADIUS, 'sanitize_callback' => 'absint']], self::SETTING_BOX_SHADOW_COLOR => ['name' => 'boxShadowColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Color', RCB_TD), 'setting' => ['default' => self::DEFAULT_BOX_SHADOW_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_BOX_SHADOW_COLOR_ALPHA => ['name' => 'boxShadowColorAlpha', 'label' => \__('Color opacity', RCB_TD), 'class' => RangeInput::class, 'unit' => '%', 'input_attrs' => ['min' => 0, 'max' => 100, 'step' => 0], 'setting' => ['default' => self::DEFAULT_BOX_SHADOW_COLOR_ALPHA, 'sanitize_callback' => 'absint']], self::HEADLINE_HOVER => ['class' => Headline::class, 'name' => 'stickyHover', 'label' => \__('Active state', RCB_TD), 'level' => 2, 'description' => \__('When the user moves the mouse over the sticky symbol or clicks on it, its color changes. These colors are also used for the menu items.', RCB_TD)], self::SETTING_HOVER_BG => ['name' => 'bubbleHoverBg', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Background color', RCB_TD), 'setting' => ['default' => self::DEFAULT_HOVER_BG, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_HOVER_BORDER_COLOR => ['name' => 'bubbleHoverBorderColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Border color', RCB_TD), 'setting' => ['default' => self::DEFAULT_HOVER_BORDER_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_HOVER_ICON_COLOR => ['name' => 'hoverIconColor', 'class' => WP_Customize_Color_Control::class, 'label' => \__('Icon and text color', RCB_TD), 'setting' => ['default' => self::DEFAULT_HOVER_ICON_COLOR, 'sanitize_callback' => 'sanitize_hex_color']], self::SETTING_HOVER_ICON_CUSTOM => ['class' => WP_Customize_Image_Control::class, 'name' => 'hoverIconCustom', 'label' => \__('Custom icon', RCB_TD)], self::SETTING_HOVER_ICON_CUSTOM_RETINA => ['class' => WP_Customize_Image_Control::class, 'name' => 'hoverIconCustomRetina', 'label' => \__('Custom icon (x2 for Retina)', RCB_TD)], self::HEADLINE_MENU => ['class' => Headline::class, 'name' => 'stickyMenu', 'label' => \__('Menu items', RCB_TD), 'description' => \sprintf(
            // translators:
            \__('You can customize the texts for the menu items in %1$sCookie Banner > Texts > Sticky legal links widget%2$s.', RCB_TD),
            '<a href="#" onClick="wp.customize.control(\'' . \DevOwl\RealCookieBanner\view\customize\banner\Texts::SETTING_STICKY_CHANGE . '\').focus()">',
            '</a>'
        ), 'level' => 2], self::SETTING_MENU_FONT_SIZE => ['name' => 'menuFontSize', 'label' => \__('Font size', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'max' => 50, 'step' => 0], 'setting' => ['default' => self::DEFAULT_MENU_FONT_SIZE, 'sanitize_callback' => 'absint']], self::SETTING_MENU_BORDER_RADIUS => ['name' => 'menuBorderRadius', 'label' => \__('Border radius', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'max' => 100, 'step' => 0], 'setting' => ['default' => self::DEFAULT_MENU_BORDER_RADIUS, 'sanitize_callback' => 'absint']], self::SETTING_MENU_ITEM_SPACING => ['name' => 'menuItemSpacing', 'label' => \__('Space between menu items', RCB_TD), 'class' => RangeInput::class, 'unit' => 'px', 'input_attrs' => ['min' => 0, 'max' => 50, 'step' => 0], 'setting' => ['default' => self::DEFAULT_MENU_ITEM_SPACING, 'sanitize_callback' => 'absint']], self::SETTING_MENU_ITEM_PADDING => ['class' => CssMarginInput::class, 'name' => 'menuItemPadding', 'label' => \__('Padding', RCB_TD), 'description' => \__('Define inner distance of the menu item.', RCB_TD), 'dashicon' => 'editor-contract', 'setting' => ['default' => self::DEFAULT_MENU_ITEM_PADDING]]]];
    }
}
