<?php

namespace DevOwl\RealCookieBanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\RealUtils\AbstractInitiator;
use DevOwl\RealCookieBanner\base\UtilsProvider;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Initiate real-utils functionality.
 * @internal
 */
class AdInitiator extends AbstractInitiator
{
    use UtilsProvider;
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function getPluginBase()
    {
        return $this;
    }
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function getHeroButton()
    {
        return [\__('Start configuration', RCB_TD), \DevOwl\RealCookieBanner\Core::getInstance()->getConfigPage()->getUrl()];
    }
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function getPluginAssets()
    {
        return $this->getCore()->getAssets();
    }
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function getRateLink()
    {
        return 'https://devowl.io/go/wordpress-org/real-cookie-banner/rate';
    }
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function getWelcomePageImageHeight()
    {
        return 280;
    }
    /**
     * Documented in AbstractInitiator.
     *
     * @codeCoverageIgnore
     */
    public function getKeyFeatures()
    {
        return [['image' => $this->getAssetsUrl(\__('key-features/presets.gif', RCB_TD)), 'title' => \__('Consent Management', RCB_TD), 'description' => \__('You can use Real Cookie Banner to store all technical and legal information about services and cookies to obtain informed consent. You can use 100+ service templates and 60+ content blocker templates to quickly and securely provide all the information you need.', RCB_TD)], ['image' => $this->getAssetsUrl(\__('key-features/content-blocker.gif', RCB_TD)), 'title' => \__('Content Blocker', RCB_TD), 'description' => \__('Themes, plugins and co. usually load scripts, styles and content that transfer personal data and set cookies before you have the consent of your visitors. You usually cannot control this by yourself. Content blockers make sure that these features are only executed after you have obtained consent.', RCB_TD)], ['image' => $this->getAssetsUrl(\__('key-features/customize-presets.gif', RCB_TD)), 'title' => \__('Customize design', RCB_TD), 'description' => \__('You can design the cookie banner according to your wishes. 20+ design templates and 200+ options give you the flexibility to customize the cookie banner perfectly to your needs. From colors and effects to texts, you can unleash your creativity! All changes are displayed in a live preview.', RCB_TD)], ['image' => $this->getAssetsUrl(\__('key-features/guided-configuration.gif', RCB_TD)), 'title' => \__('Guided configuration', RCB_TD), 'description' => \__('After installation, the checklist will guide you through all steps to be able to set up Real Cookie Banner in a legally compliant manner. We also explain the legal basis of features and the legal consequences if you change settings. So, you can quickly and safely set up your cookie banner!', RCB_TD)], ['image' => $this->getAssetsUrl(\__('key-features/list-of-consents.gif', RCB_TD)), 'title' => \__('Documentation of consents', RCB_TD), 'description' => \__('According to the GDPR, you have to prove that a visitor has consented to cookies and processing of personal data if he or she doubts this. We document consent completely and make it possible to trace the origin of the consent afterwards. You are on the safe side even in the worst case!', RCB_TD)], ['image' => $this->getAssetsUrl(\__('key-features/native-menu.gif', RCB_TD)), 'title' => \__('Native in WordPress', RCB_TD), 'description' => \__('Real Cookie Banner is a cookie plugin specially designed for WordPress. It is fully installed in your WordPress as a native plugin. All consents are processed and stored on your server. Nothing is downloaded from a cloud in your visitor\'s browser, which avoids further legal issues.', RCB_TD)]];
    }
}
