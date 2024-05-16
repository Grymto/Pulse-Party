<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\BlockerTemplate;
/**
 * Middleware to add a tag with label when TCF is required and not active. It also creates an array in `consumerData['tcfVendorConfigurations']`
 * with already created TCF vendor configurations (and their respective ID on the consumer environment).
 * @internal
 */
class TcfMiddleware extends AbstractTemplateMiddleware
{
    // Documented in AbstractTemplateMiddleware
    public function beforePersistTemplate($template, &$allTemplates)
    {
        // TODO [TCF]: currently, the service cloud does not support TCF vendors, so we need to reset the `serviceTemplateIdentifiers` to an empty array
        //             and additionally set the vendor ID for Google Adsense
        if ($template instanceof BlockerTemplate && \in_array($template->identifier, ['google-adsense-tcf'], \true)) {
            $template->serviceTemplateIdentifiers = [];
            $template->tcfVendorIds = [755];
        }
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeUsingTemplate($template)
    {
        if ($template instanceof BlockerTemplate) {
            $data = [];
            $existing = $this->getVariableResolver()->resolveDefault('tcfVendors.created', []);
            foreach ($template->tcfVendorIds as $vendorId) {
                foreach ($existing as $existingRow) {
                    if ($existingRow['vendorId'] === $vendorId) {
                        $data[] = $existingRow;
                        continue 2;
                    }
                }
                // No vendor configuration found for this
                $data[] = [
                    'vendorId' => $vendorId,
                    'vendorConfigurationId' => \false,
                    // TODO [TCF] shouldn't this be more generic by reading from a list of available networks?
                    'createAdNetwork' => $vendorId === 755 ? 'google-adsense' : null,
                ];
            }
            $template->consumerData['tcfVendorConfigurations'] = $data;
        }
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeRetrievingTemplate($template)
    {
        $variableResolver = $this->getVariableResolver();
        $labelDisabled = $variableResolver->resolveDefault('i18n.TcfMiddleware.disabled', 'TCF required');
        $labelTcfDisabledTooltip = $variableResolver->resolveDefault('i18n.TcfMiddleware.disabledTooltip', 'This template requires the integration of TCF, as the provider of this template uses this standard. Please activate this in the settings to be able to block this service.');
        if ($template instanceof BlockerTemplate) {
            // TODO [TCF]: currently, yes, we do not have TCF compatibility in our service cloud yet, so `createAdNetwork` is part of `consumerData` instead of a property of `BlockerTemplate`
            if (\in_array($template->identifier, ['google-adsense-tcf'], \true)) {
                $isTcfActive = $variableResolver->resolveDefault('isTcfActive', \false);
                if (!$isTcfActive) {
                    $template->consumerData['tags'][$labelDisabled] = $labelTcfDisabledTooltip;
                }
                $template->consumerData['createAdNetwork'] = 'google-adsense';
            }
        }
    }
}
