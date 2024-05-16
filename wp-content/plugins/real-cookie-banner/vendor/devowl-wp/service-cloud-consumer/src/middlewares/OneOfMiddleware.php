<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\ServiceTemplate;
/**
 * Middleware to active some fields with `WhenOneOf` statements.
 * @internal
 */
class OneOfMiddleware extends AbstractTemplateMiddleware
{
    // Documented in AbstractTemplateMiddleware
    public function beforePersistTemplate($template, &$allTemplates)
    {
        $variableResolver = $this->getVariableResolver();
        // `isDisabled`
        if (\is_array($template->enabledWhenOneOf) && \count($template->enabledWhenOneOf) > 0) {
            $isDisabled = !$variableResolver->resolveOneOf($template->enabledWhenOneOf, $template);
            if ($isDisabled) {
                $disabledTooltip = $variableResolver->resolveDefault('i18n.OneOfMiddleware.disabledTooltip', 'This template is currently disabled because a dependency component is not installed or the desired function is not active.');
                $disabledText = $variableResolver->resolveDefault('i18n.disabled', 'Disabled');
                $template->consumerData['tags'][$disabledText] = $disabledTooltip;
            }
            $template->consumerData['isDisabled'] = $isDisabled;
        } elseif (!isset($template->consumerData['isDisabled'])) {
            $template->consumerData['isDisabled'] = \false;
        }
        // `isRecommended`
        if (\is_array($template->recommendedWhenOneOf) && \count($template->recommendedWhenOneOf) > 0) {
            $template->consumerData['isRecommended'] = $variableResolver->resolveOneOf($template->recommendedWhenOneOf, $template);
        } elseif (!isset($template->consumerData['isRecommended'])) {
            $template->consumerData['isRecommended'] = \false;
        }
        // Service: `shouldUncheckContentBlockerCheckboxWhenOneOf`
        if ($template instanceof ServiceTemplate && \is_array($template->shouldUncheckContentBlockerCheckboxWhenOneOf) && \count($template->shouldUncheckContentBlockerCheckboxWhenOneOf) > 0) {
            $template->shouldUncheckContentBlockerCheckbox = $variableResolver->resolveOneOf($template->shouldUncheckContentBlockerCheckboxWhenOneOf, $template);
        }
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeUsingTemplate($template)
    {
        // Silence is golden.
    }
}
