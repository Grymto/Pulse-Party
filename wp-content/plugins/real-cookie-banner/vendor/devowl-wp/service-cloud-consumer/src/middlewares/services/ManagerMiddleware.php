<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\services;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractTemplateMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\ServiceTemplate;
/**
 * Mark manager-compatible services with a tag.
 * @internal
 */
class ManagerMiddleware extends AbstractTemplateMiddleware
{
    const IDENTIFIER_GOOGLE_TAG_MANAGER = 'gtm';
    const IDENTIFIER_MATOMO_TAG_MANAGER = 'mtm';
    const SET_COOKIES_AFTER_CONSENT_VIA_JAVASCRIPT = 'none';
    const SET_COOKIES_AFTER_CONSENT_VIA_GOOGLE_TAG_MANAGER = 'googleTagManager';
    const SET_COOKIES_AFTER_CONSENT_VIA_GOOGLE_TAG_MANAGER_WITH_GCM = 'googleTagManagerWithGcm';
    const SET_COOKIES_AFTER_CONSENT_VIA_MATOMO_TAG_MANAGER = 'matomoTagManager';
    const TAG_MANAGER_IDENTIFIERS = [self::IDENTIFIER_GOOGLE_TAG_MANAGER, self::IDENTIFIER_MATOMO_TAG_MANAGER];
    // Documented in AbstractTemplateMiddleware
    public function beforePersistTemplate($template, &$allTemplates)
    {
        if ($template instanceof ServiceTemplate) {
            $variableResolver = $this->getVariableResolver();
            $manager = $variableResolver->resolveRequired('manager');
            $isGcm = $variableResolver->resolveRequired('isGcm');
            if ($manager === self::SET_COOKIES_AFTER_CONSENT_VIA_GOOGLE_TAG_MANAGER_WITH_GCM && !$isGcm) {
                $manager = self::SET_COOKIES_AFTER_CONSENT_VIA_GOOGLE_TAG_MANAGER;
            }
            $tooltip = $variableResolver->resolveDefault('i18n.ManagerMiddleware.tooltip', 'This service template is optimized to work with %s.');
            $disabledTooltip = $variableResolver->resolveDefault('i18n.ManagerMiddleware.disabledTooltip', 'Please activate %s in settings to use this template.');
            $disabledText = $variableResolver->resolveDefault('i18n.disabled', 'Disabled');
            $disabled = \false;
            switch ($manager) {
                case 'none':
                    if (\in_array($template->identifier, self::TAG_MANAGER_IDENTIFIERS, \true)) {
                        $template->consumerData['tags'][$disabledText] = \sprintf($disabledTooltip, $template->identifier === self::IDENTIFIER_GOOGLE_TAG_MANAGER ? 'Google Tag Manager' : 'Matomo Tag Manager');
                        $disabled = \true;
                    }
                    break;
                case self::SET_COOKIES_AFTER_CONSENT_VIA_GOOGLE_TAG_MANAGER:
                    if (!empty($template->tagManagerOptInEventName) || !empty($template->tagManagerOptOutEventName)) {
                        $template->consumerData['tags']['GTM'] = \sprintf($tooltip, 'Google Tag Manager');
                    }
                    break;
                case self::SET_COOKIES_AFTER_CONSENT_VIA_MATOMO_TAG_MANAGER:
                    if (!empty($template->tagManagerOptInEventName) || !empty($template->tagManagerOptOutEventName)) {
                        $template->consumerData['tags']['MTM'] = \sprintf($tooltip, 'Matomo Tag Manager');
                    }
                    break;
                default:
                    break;
            }
            if ($disabled) {
                $template->consumerData['isDisabled'] = \true;
            }
        }
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeUsingTemplate($template)
    {
        // Silence is golden.
    }
}
