<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\services;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractTemplateMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\ServiceTemplate;
/**
 * Set technical definitions host depending on consumer environment.
 * @internal
 */
class TechnicalDefinitionsMiddleware extends AbstractTemplateMiddleware
{
    /**
     * nip.io
     */
    const HOST_TYPE_MAIN = 'main';
    /**
     * .nip.io
     */
    const HOST_TYPE_MAIN_WITH_ALL_SUBDOMAINS = 'main+subdomains';
    /**
     * feat.nip.io
     */
    const HOST_TYPE_CURRENT = 'current';
    /**
     * https://feat.nip.io
     */
    const HOST_TYPE_CURRENT_PROTOCOL = 'current+protocol';
    /**
     * .feat.nip.io
     */
    const HOST_TYPE_CURRENT_WITH_ALL_SUBDOMAINS = 'current+subdomains';
    // Documented in AbstractTemplateMiddleware
    public function beforePersistTemplate($template, &$allTemplates)
    {
        // Silence is golden.
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeUsingTemplate($template)
    {
        if ($template instanceof ServiceTemplate && \is_array($template->technicalDefinitions)) {
            $variableResolver = $this->getVariableResolver();
            foreach ($template->technicalDefinitions as &$td) {
                $host = $td['host'] ?? '';
                if (\in_array($host, [self::HOST_TYPE_CURRENT, self::HOST_TYPE_CURRENT_PROTOCOL, self::HOST_TYPE_CURRENT_WITH_ALL_SUBDOMAINS, self::HOST_TYPE_MAIN, self::HOST_TYPE_MAIN_WITH_ALL_SUBDOMAINS], \true)) {
                    $td['host'] = $variableResolver->resolveRequired(\sprintf('consumer.host.%s', $host));
                }
            }
        }
    }
}
