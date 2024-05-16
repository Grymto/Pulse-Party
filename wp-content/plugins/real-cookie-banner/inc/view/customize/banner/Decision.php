<?php

namespace DevOwl\RealCookieBanner\view\customize\banner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\AbstractCustomizePanel;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls\CustomHTML;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls\Headline;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\view\BannerCustomize;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Content decision options.
 * @internal
 */
class Decision
{
    use UtilsProvider;
    /**
     * Available permutations for the button order.
     *
     * @see https://phpsandbox.io/n/real-cookie-banner-button-order-premutation-9uk9m
     */
    const BUTTON_ORDER_PERMUTATIONS = [['all', 'essential', 'individual', 'save'], ['all', 'essential', 'save', 'individual'], ['all', 'individual', 'essential', 'save'], ['all', 'individual', 'save', 'essential'], ['all', 'save', 'essential', 'individual'], ['all', 'save', 'individual', 'essential'], ['essential', 'all', 'individual', 'save'], ['essential', 'all', 'save', 'individual'], ['essential', 'individual', 'all', 'save'], ['essential', 'individual', 'save', 'all'], ['essential', 'save', 'all', 'individual'], ['essential', 'save', 'individual', 'all'], ['individual', 'all', 'essential', 'save'], ['individual', 'all', 'save', 'essential'], ['individual', 'essential', 'all', 'save'], ['individual', 'essential', 'save', 'all'], ['individual', 'save', 'all', 'essential'], ['individual', 'save', 'essential', 'all'], ['save', 'all', 'essential', 'individual'], ['save', 'all', 'individual', 'essential'], ['save', 'essential', 'all', 'individual'], ['save', 'essential', 'individual', 'all'], ['save', 'individual', 'all', 'essential'], ['save', 'individual', 'essential', 'all']];
    const SECTION = BannerCustomize::PANEL_MAIN . '-decision';
    const CUSTOM_HTML_BUTTON_TYPE_DIFFERS_NOTICE = self::SECTION . '-custom-html-button-type-differs-notice';
    const CUSTOM_HTML_LEGAL_NOTICE_ALL = self::SECTION . '-custom-html-legal-notice-all';
    const CUSTOM_HTML_LEGAL_NOTICE_ESSENTIALS = self::SECTION . '-custom-html-legal-notice-essentials';
    const CUSTOM_HTML_LEGAL_NOTICE_INDIVIDUAL = self::SECTION . '-custom-html-legal-notice-individual';
    const HEADLINE_SERVICE_GROUPS = self::SECTION . '-headline-service-groups';
    const SETTING = RCB_OPT_PREFIX . '-banner-decision';
    const SETTING_ACCEPT_ALL = self::SETTING . '-accept-all';
    const SETTING_ACCEPT_ESSENTIALS = self::SETTING . '-accept-essentials';
    const SETTING_SHOW_CLOSE_ICON = self::SETTING . '-show-close-icon';
    const SETTING_ACCEPT_INDIVIDUAL = self::SETTING . '-accept-individual';
    const SETTING_SHOW_GROUPS = self::SETTING . '-show-groups';
    const SETTING_GROUPS_FIRST_VIEW = self::SETTING . '-groups-first-view';
    const SETTING_SAVE_BUTTON = self::SETTING . '-save-button';
    const SETTING_BUTTON_ORDER = self::SETTING . '-button-order';
    const DEFAULT_ACCEPT_ALL = 'button';
    const DEFAULT_ACCEPT_ESSENTIALS = 'button';
    const DEFAULT_SHOW_CLOSE_ICON = \false;
    const DEFAULT_ACCEPT_INDIVIDUAL = 'link';
    const DEFAULT_SHOW_GROUPS = \false;
    const DEFAULT_GROUPS_FIRST_VIEW = \false;
    const DEFAULT_SAVE_BUTTON = 'always';
    const DEFAULT_BUTTON_ORDER = 'all,essential,save,individual';
    /**
     * Return arguments for this section.
     */
    public function args()
    {
        $textButtonTypeDiffers = \sprintf('<div class="notice notice-warning inline below-h2 notice-alt" style="margin: 10px 0 0 0"><p>%s</p></div>', \__('The options "Accept all" and "Continue without consent" should be of the same type. Therefore, both should be a button or a link.', RCB_TD));
        $textLegalNotice = \sprintf('<div class="notice notice-warning inline below-h2 notice-alt" style="margin: 10px 0 0 0"><p>%s</p></div>', \__('Please note that from a legal point of view you have to give your visitor this option. With the currently selected setting, you are taking a risk.', RCB_TD));
        $textLegalNoticeEssentials = \sprintf('<div class="notice notice-warning inline below-h2 notice-alt" style="margin: 10px 0 0 0"><p>%s</p></div>', \__('Please note that from a legal perspective, you must give your visitor an equivalent way to give or refuse consent. In some countries of the EU it is also allowed to display an "X" in the upper right corner of the cookie banner instead of a button/link. With the currently selected setting you are taking a risk!', RCB_TD));
        return ['name' => 'decision', 'title' => \__('Consent options', RCB_TD), 'controls' => [self::CUSTOM_HTML_BUTTON_TYPE_DIFFERS_NOTICE => ['class' => CustomHTML::class, 'name' => 'customHtmlDecisionButtonTypeDiffers', 'description' => $textButtonTypeDiffers], self::SETTING_ACCEPT_ALL => ['name' => 'acceptAll', 'label' => \__('Accept all', RCB_TD), 'description' => \sprintf(
            // translators:
            \__('According to the %1$sGDPR recital 32%2$s, you are not allowed to pre-select services in the form of pre-selected checkboxes for the visitor. However, the GDPR and ePrivacy Directive do not prohibit the use of a button to agree to all services, if there is also a button to agree only to the essential services or to make a user-defined selection.', RCB_TD),
            '<a href="' . \__('https://gdpr-info.eu/recitals/no-32/', RCB_TD) . '" target="_blank">',
            '</a>'
        ), 'type' => 'select', 'choices' => ['button' => \__('Button', RCB_TD), 'link' => \__('Link', RCB_TD), 'hide' => \__('Hide', RCB_TD)], 'setting' => ['default' => self::DEFAULT_ACCEPT_ALL]], self::CUSTOM_HTML_LEGAL_NOTICE_ALL => ['class' => CustomHTML::class, 'name' => 'customHtmlDecisionLegalNoticeAll', 'description' => $textLegalNotice], self::SETTING_ACCEPT_ESSENTIALS => ['name' => 'acceptEssentials', 'label' => \__('Continue without consent', RCB_TD), 'description' => \__('The GDPR (EU 2016/679) and ePrivacy Directive (EU 2009/136/EC) defines that visitors must be able to easily reject services, whereby essential services can be set in any case. Therefore, the visitor should be given this option in a similar manner to the "Accept all" button/link.', RCB_TD), 'type' => 'select', 'choices' => ['button' => \__('Button', RCB_TD), 'link' => \__('Link', RCB_TD), 'hide' => \__('Hide', RCB_TD)], 'setting' => ['default' => self::DEFAULT_ACCEPT_ESSENTIALS]], self::CUSTOM_HTML_LEGAL_NOTICE_ESSENTIALS => ['class' => CustomHTML::class, 'name' => 'customHtmlDecisionLegalNoticeEssentials', 'description' => $textLegalNoticeEssentials], self::SETTING_SHOW_CLOSE_ICON => ['name' => 'showCloseIcon', 'label' => \__('Show close icon, to continue without consent', RCB_TD), 'description' => \__('An "X" icon is displayed at the top right of the cookie banner, which allows website visitors to close the cookie banner. It has the same function as clicking on the "Continue without consent" button/link.', RCB_TD), 'type' => 'checkbox', 'setting' => [
            // In Italian, the checkbox is required
            'default' => \get_locale() === 'it_IT' ? \true : self::DEFAULT_SHOW_CLOSE_ICON,
            'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox'],
        ]], self::SETTING_ACCEPT_INDIVIDUAL => ['name' => 'acceptIndividual', 'label' => \__('Individual privacy preferences', RCB_TD), 'description' => \__('According to the GDPR and the ePrivacy Directive, the user must be free to choose which services to use and be informed about their purpose before giving consent. Therefore, the user must be given the opportunity to access the page with the individual privacy settings.', RCB_TD), 'type' => 'select', 'choices' => ['button' => \__('Button', RCB_TD), 'link' => \__('Link', RCB_TD), 'hide' => \__('Hide', RCB_TD)], 'setting' => ['default' => self::DEFAULT_ACCEPT_INDIVIDUAL]], self::CUSTOM_HTML_LEGAL_NOTICE_INDIVIDUAL => ['class' => CustomHTML::class, 'name' => 'customHtmlDecisionLegalNoticeIndividual', 'description' => $textLegalNotice], self::SETTING_BUTTON_ORDER => ['name' => 'buttonOrder', 'label' => \__('Order of the buttons', RCB_TD), 'description' => \sprintf(
            // translators:
            \__('According to the <a href="%s" target="_blank">guidance of the data protection authorities in Germany (German)</a>, there must be an equivalent way for your visitors to express their choice (agree to everything, continue without consent or an indivudual decision). Which order of options is considered equivalent is unclear. Therefore, the options should be arranged according to the cultural conditions of the main target country.', RCB_TD),
            \__('https://www.datenschutzkonferenz-online.de/media/oh/20211220_oh_telemedien.pdf', RCB_TD)
        ), 'type' => 'select', 'choices' => self::getButtonOrderPermutations(), 'setting' => ['default' => self::DEFAULT_BUTTON_ORDER]], self::HEADLINE_SERVICE_GROUPS => ['class' => Headline::class, 'name' => 'serviceGroups', 'label' => \__('Service groups as bullet list', RCB_TD)], self::SETTING_SHOW_GROUPS => ['name' => 'showGroups', 'label' => \__('Show service groups as bullet list', RCB_TD), 'type' => 'checkbox', 'setting' => ['default' => self::DEFAULT_SHOW_GROUPS, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_GROUPS_FIRST_VIEW => ['name' => 'groupsFirstView', 'label' => \__('Custom choice in first view', RCB_TD), 'type' => 'rcbGroupsFirstView', 'setting' => ['default' => self::DEFAULT_GROUPS_FIRST_VIEW, 'sanitize_callback' => [AbstractCustomizePanel::class, 'sanitize_checkbox']]], self::SETTING_SAVE_BUTTON => ['name' => 'saveButton', 'label' => \__('Save button', RCB_TD), 'type' => 'radio', 'choices' => ['always' => \__('Show save button always', RCB_TD), 'afterChange' => \__('Show save button only after a change by the user', RCB_TD), 'afterChangeAll' => \__('Text of "Accept all" changes when user changes selection', RCB_TD)], 'setting' => ['default' => self::DEFAULT_SAVE_BUTTON]]]];
    }
    /**
     * Calculate button order permutations with translated select dropdown in an associative array.
     */
    public static function getButtonOrderPermutations()
    {
        $result = [];
        foreach (self::BUTTON_ORDER_PERMUTATIONS as $permutation) {
            $key = \join(',', $permutation);
            $result[$key] = \join(', ', \array_map(function ($button) {
                switch ($button) {
                    case 'all':
                        return \_x('Accept all', 'legal-text', RCB_TD);
                    case 'essential':
                        return \_x('Continue without consent', 'legal-text', RCB_TD);
                    case 'individual':
                        return \_x('Individual privacy preferences', 'legal-text', RCB_TD);
                    case 'save':
                        return \_x('Save custom choices', 'legal-text', RCB_TD);
                    default:
                        return '';
                }
            }, $permutation));
        }
        return $result;
    }
}
