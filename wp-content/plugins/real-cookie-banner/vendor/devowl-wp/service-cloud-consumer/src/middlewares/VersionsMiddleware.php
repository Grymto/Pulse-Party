<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares;

/**
 * Middleware to replace `consumerData['versions'] (number[])` with the template object when using.
 * It automatically omits the latest version from the array.
 * @internal
 */
class VersionsMiddleware extends AbstractTemplateMiddleware
{
    // Documented in AbstractTemplateMiddleware
    public function beforePersistTemplate($template, &$allTemplates)
    {
        // Silence is golden.
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeUsingTemplate($template)
    {
        if (isset($template->consumerData['versions'])) {
            $versions =& $template->consumerData['versions'];
            foreach ($versions as $key => $version) {
                // Remove same version
                if ($version === $template->version) {
                    unset($versions[$key]);
                }
            }
            \rsort($versions);
            if (\count($versions) === 0) {
                return;
            }
            // Avoid recursive version resolving
            $this->suspend(\true);
            // Retrieve version templates
            $retrieved = $this->getConsumer()->retrieveBy('versions', \array_merge([$template->identifier], \array_values($versions)));
            $versions = \array_values(\array_map(function ($template) {
                return $template->use();
            }, $retrieved));
            $this->suspend(\false);
        }
    }
}
