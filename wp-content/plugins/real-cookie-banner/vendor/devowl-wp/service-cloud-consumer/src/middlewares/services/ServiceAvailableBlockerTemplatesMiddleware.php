<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\services;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractPoolMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\BlockerTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\ServiceTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\Utils;
/**
 * Middleware to find all content blockers depending on a service and make them available
 * as custom data in a service template.
 *
 * This is useful to show a dropdown of available content blockers for a service template.
 * @internal
 */
class ServiceAvailableBlockerTemplatesMiddleware extends AbstractPoolMiddleware
{
    // Documented in AbstractPoolMiddleware
    public function beforePersistTemplateWithinPool($template, &$typeClassToAllTemplates)
    {
        if ($template instanceof ServiceTemplate && isset($typeClassToAllTemplates[BlockerTemplate::class])) {
            /**
             * Blocker templates.
             *
             * @var BlockerTemplate[]
             */
            $blockerTemplates = $typeClassToAllTemplates[BlockerTemplate::class];
            $variableResolver = $this->getVariableResolver();
            $tooltipText = $variableResolver->resolveDefault('i18n.ServiceAvailableBlockerTemplatesMiddleware.tooltip', 'A suitable content blocker for this service can be created automatically.');
            $tier = $variableResolver->resolveRequired('tier');
            /**
             * Existing service templates.
             *
             * @var ServiceTemplate[]
             */
            $existingServices = $variableResolver->resolveRequired('created.' . ServiceTemplate::class);
            /**
             * Existing blocker templates.
             *
             * @var ServiceTemplate[]
             */
            $existingBlocker = $variableResolver->resolveRequired('created.' . BlockerTemplate::class);
            $existingBlockerIdentifiers = \array_map(function ($item) {
                return $item->identifier;
            }, $existingBlocker);
            $contentBlockerTemplates = $template->consumerData['contentBlockerTemplates'] ?? [];
            // If service is already created, the user has perhaps intentionally created not the
            // suitable content blocker -> not show this tag.
            if (!Utils::in_array_column($existingServices, 'identifier', $template->identifier)) {
                // Iterate all blocker templates and check which one is not yet created
                foreach ($blockerTemplates as $blockerTemplate) {
                    $isDisabled = $blockerTemplate->consumerData['isDisabled'] ?? \false;
                    if ($isDisabled || $blockerTemplate->isHidden || !\in_array($template->identifier, $blockerTemplate->serviceTemplateIdentifiers, \true) || \in_array($blockerTemplate->identifier, $existingBlockerIdentifiers, \true) || $blockerTemplate->tier !== AbstractTemplate::TIER_FREE && $tier === AbstractTemplate::TIER_FREE) {
                        continue;
                    }
                    $template->consumerData['tags']['Content Blocker'] = $tooltipText;
                    $contentBlockerTemplates[] = ['identifier' => $blockerTemplate->identifier, 'name' => $blockerTemplate->name, 'subHeadline' => $blockerTemplate->subHeadline];
                }
            }
            $template->consumerData['contentBlockerTemplates'] = $contentBlockerTemplates;
        }
    }
    // Documented in AbstractPoolMiddleware
    public function afterPersistTemplatesWithinPool($consumer, &$typeClassToAllTemplates)
    {
        // Silence is golden.
    }
}
