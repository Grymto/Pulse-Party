<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\BlockerTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\ServiceTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\Utils;
/**
 * Middleware to add a tag with label when the preset already exists.
 *
 * The existing `AbtractTemplate` instances from the variable resolver `created.{TemplateClass::class}` need to have a `consumerData['id']` property.
 * @internal
 */
class ExistsMiddleware extends AbstractTemplateMiddleware
{
    // Documented in AbstractTemplateMiddleware
    public function beforePersistTemplate($template, &$allTemplates)
    {
        // Silence is golden.
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeUsingTemplate($template)
    {
        // Silence is golden.
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeRetrievingTemplate($template)
    {
        $variableResolver = $this->getVariableResolver();
        /**
         * Templates.
         *
         * @var AbstractTemplate[]
         */
        $existing = $variableResolver->resolveRequired('created.' . \get_class($template));
        $labelAlreadyCreated = $variableResolver->resolveDefault('i18n.ExistsMiddleware.alreadyCreated', 'Already created');
        $tooltipBlocker = $variableResolver->resolveDefault('i18n.ExistsMiddleware.blockerAlreadyCreatedTooltip', 'You have already created a Content Blocker with this template.');
        $tooltipService = $variableResolver->resolveDefault('i18n.ExistsMiddleware.serviceAlreadyCreatedTooltip', 'You have already created a Service (Cookie) with this template.');
        $exists = \false;
        $tooltipText = '';
        $exists = Utils::in_array_column($existing, 'identifier', $template->identifier, \true);
        if ($template instanceof BlockerTemplate) {
            $tooltipText = $tooltipBlocker;
        } elseif ($template instanceof ServiceTemplate) {
            $tooltipText = $tooltipService;
        }
        if ($exists) {
            if (isset($exists->consumerData['id'])) {
                $template->consumerData['id'] = $exists->consumerData['id'];
            }
            $template->consumerData['tags'][$labelAlreadyCreated] = $tooltipText;
        }
        $template->consumerData['isCreated'] = $exists ? \true : \false;
    }
}
