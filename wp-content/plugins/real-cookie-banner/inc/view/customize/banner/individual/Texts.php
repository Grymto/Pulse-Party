<?php

namespace DevOwl\RealCookieBanner\view\customize\banner\individual;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls\Headline;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls\TinyMCE;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\view\BannerCustomize;
use DevOwl\RealCookieBanner\view\customize\banner\Texts as BannerTexts;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Cookie banner texts for "Individual Privacy" settings.
 * @internal
 */
class Texts
{
    use UtilsProvider;
    const SECTION = BannerCustomize::PANEL_MAIN . '-individual-texts';
    const HEADLINE_GENERAL = self::SECTION . '-headline-general';
    const SETTING = RCB_OPT_PREFIX . '-individual-texts';
    const SETTING_HEADLINE = self::SETTING . '-headline';
    const SETTING_DESCRIPTION = self::SETTING . '-description';
    const SETTING_SAVE = self::SETTING . '-save';
    const SETTING_SHOW_MORE = self::SETTING . '-show-more';
    const SETTING_HIDE_MORE = self::SETTING . '-hide-more';
    const SETTING_POSTAMBLE = self::SETTING . '-postamble';
    /**
     * Return arguments for this section.
     */
    public function args()
    {
        $defaultButtonTexts = self::getDefaultButtonTexts();
        return ['name' => 'individualTexts', 'title' => \__('Texts', RCB_TD), 'description' => BannerTexts::getDescription(), 'controls' => [self::HEADLINE_GENERAL => ['class' => Headline::class, 'label' => \__('General', RCB_TD), 'level' => 3, 'isSubHeadline' => \true], self::SETTING_HEADLINE => ['name' => 'headline', 'label' => \__('Headline', RCB_TD), 'setting' => ['default' => $defaultButtonTexts['headline'], 'allowEmpty' => \true]], self::SETTING_DESCRIPTION => ['name' => 'description', 'label' => \__('Description', RCB_TD), 'description' => \__('Use <code>{{privacyPolicy}}privacy policy{{/privacyPolicy}}</code> as a placeholder for the privacy policy link.', RCB_TD), 'class' => TinyMCE::class, 'type' => 'textarea', 'setting' => ['default' => $defaultButtonTexts['description'], 'sanitize_callback' => 'wp_kses_post', 'allowEmpty' => \true]], self::SETTING_SAVE => ['name' => 'save', 'label' => \__('"Save" button/link', RCB_TD), 'setting' => ['default' => $defaultButtonTexts['save']]], self::SETTING_SHOW_MORE => ['name' => 'showMore', 'label' => \__('"Show service information" link', RCB_TD), 'setting' => ['default' => $defaultButtonTexts['showMore']]], self::SETTING_HIDE_MORE => ['name' => 'hideMore', 'label' => \__('"Hide service information" link', RCB_TD), 'setting' => ['default' => $defaultButtonTexts['hideMore']]], self::SETTING_POSTAMBLE => ['name' => 'postamble', 'label' => \__('Additional teachings below service groups', RCB_TD), 'description' => \__('If you need to place additional instructions in your country, such as an introduction to deleting cookies, they can be displayed. Typically only necessary if you are deliberately addressing other jurisdictions outside the EU.', RCB_TD), 'class' => TinyMCE::class, 'type' => 'textarea', 'setting' => ['default' => $defaultButtonTexts['postamble'], 'sanitize_callback' => 'wp_kses_post', 'allowEmpty' => \true]]]];
    }
    /**
     * Get the button default texts.
     */
    public static function getDefaultButtonTexts()
    {
        $tempTd = Hooks::getInstance()->createTemporaryTextDomain();
        $defaults = ['headline' => \_x('Individual privacy preferences', 'legal-text', Hooks::TD_FORCED), 'description' => \sprintf('%s<br/><br/>%s<br/><br/>%s', \_x('We use cookies and similar technologies on our website and process your personal data (e.g. IP address), for example, to personalize content and ads, to integrate media from third-party providers or to analyze traffic on our website. Data processing may also happen as a result of cookies being set. We share this data with third parties that we name in the privacy settings.', 'legal-text', Hooks::TD_FORCED), \_x('The data processing may take place with your consent or on the basis of a legitimate interest, which you can object to in the privacy settings. You have the right not to consent and to change or revoke your consent at a later time. For more information on the use of your data, please visit our {{privacyPolicy}}privacy policy{{/privacyPolicy}}.', 'legal-text', Hooks::TD_FORCED), \_x('Below you will find an overview of all services used by this website. You can view detailed information about each service and agree to them individually or exercise your right to object.', 'legal-text', Hooks::TD_FORCED)), 'save' => \_x('Save custom choices', 'legal-text', Hooks::TD_FORCED), 'showMore' => \_x('Show service information', 'legal-text', Hooks::TD_FORCED), 'hideMore' => \_x('Hide service information', 'legal-text', Hooks::TD_FORCED), 'postamble' => ''];
        $tempTd->teardown();
        return $defaults;
    }
}
