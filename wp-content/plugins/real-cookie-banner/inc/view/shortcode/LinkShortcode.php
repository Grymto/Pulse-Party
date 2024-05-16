<?php

namespace DevOwl\RealCookieBanner\view\shortcode;

use DevOwl\RealCookieBanner\Assets;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\view\Checklist;
use DevOwl\RealCookieBanner\view\checklist\Shortcode;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Constants;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Shortcode for:
 *
 * - Link/Button to edit consent
 * - Link/Button to view history of consent
 * - Link/Button to revoke consent
 *
 * There is also a UI shortcode builder: `linkForm.tsx`
 * @internal
 */
class LinkShortcode
{
    use UtilsProvider;
    const TAG = 'rcb-consent';
    const BUTTON_CLICKED_IDENTIFIER = 'shortcode_revoke';
    const DEFAULT_TAG = 'a';
    const ALLOWED_TYPES = ['history', 'revoke', 'change'];
    const ALLOWED_TAGS = [self::DEFAULT_TAG, 'button'];
    /**
     * Render shortcode HTML and enqueue scripts and styles.
     *
     * @param mixed $atts
     * @return string
     */
    public static function render($atts)
    {
        $core = Core::getInstance();
        $atts = \shortcode_atts(['id' => '', 'class' => '', 'type' => '', 'tag' => self::DEFAULT_TAG, 'text' => '', 'successmessage' => ''], $atts, self::TAG);
        // Validate
        if (!\in_array($atts['tag'], self::ALLOWED_TAGS, \true)) {
            $atts['tag'] = self::DEFAULT_TAG;
        }
        if (empty($atts['text'])) {
            return \__('Please provide a `text` attribute in your shortcode!', RCB_TD);
        }
        if (empty($atts['type']) || !\in_array($atts['type'], self::ALLOWED_TYPES, \true)) {
            return \sprintf(
                // translators:
                \__('Please provide a `type` attribute in your shortcode. Allowed: %s!', RCB_TD),
                \join(',', self::ALLOWED_TYPES)
            );
        }
        // Force to show banner
        $core->getAssets()->enqueue_scripts_and_styles(Constants::ASSETS_TYPE_FRONTEND);
        Checklist::getInstance()->toggle(Shortcode::IDENTIFIER, \true);
        return \sprintf('<%s href="%s" id="%s" data-success-message="%s" class="rcb-sc-link rcb-sc-link-%s %s">%s</%s>', $atts['tag'], '#consent-' . $atts['type'], !empty($atts['id']) ? \esc_attr($atts['id']) : 'rcb-sc-link-' . $atts['type'], \esc_attr($atts['successmessage']), $atts['type'], \esc_attr($atts['class']), \esc_html($atts['text']), $atts['tag']);
    }
}
