<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
/**
 * Abstract implementation of a middleware for templates.
 * @internal
 */
abstract class AbstractTemplateMiddleware extends AbstractMiddleware
{
    /**
     * Before persisting the template instance to the storage we can modify it.
     *
     * Example: Calculate `recommendedWhenOneOf`.
     *
     * @param AbstractTemplate $template
     * @param AbstractTemplate[] $allTemplates
     * @return void
     */
    public abstract function beforePersistTemplate($template, &$allTemplates);
    /**
     * Before using the template (e.g. expose it to the frontend UI form) we can modify it.
     *
     * Example: Replace variables with values.
     *
     * @param AbstractTemplate $template
     * @return void
     */
    public abstract function beforeUsingTemplate($template);
    /**
     * Before the template got read through `retrieve` or `retrieveBy`.
     *
     * @param AbstractTemplate $template
     * @return void
     */
    public function beforeRetrievingTemplate($template)
    {
        // Silence is golden.
    }
}
