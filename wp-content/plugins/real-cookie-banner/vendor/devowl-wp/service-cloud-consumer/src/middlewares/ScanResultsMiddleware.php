<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares;

/**
 * Allows to read scan results for a given service and blocker template.
 * @internal
 */
class ScanResultsMiddleware extends AbstractTemplateMiddleware
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
        $scanResults = $variableResolver->resolveDefault('serviceScan', []);
        $scanIgnored = $variableResolver->resolveDefault('serviceScanIgnored', []);
        $templateScanResult = $scanResults[$template->identifier] ?? null;
        $template->consumerData['scan'] = \is_array($templateScanResult) ? $templateScanResult : \false;
        $template->consumerData['isIgnored'] = \in_array($template->identifier, $scanIgnored, \true);
    }
}
