<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer\ServiceCloudConsumer;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
/**
 * Abstract implementation of a middleware for templates with a pool.
 * @internal
 */
abstract class AbstractPoolMiddleware extends AbstractMiddleware
{
    /**
     * Before persisting the template instance when all consumers within a pool got downloaded.
     *
     * Example: Calculate `contentBlockerTemplates` for `ServiceTemplate`.
     *
     * @param AbstractTemplate $template
     * @param AbstractTemplate[] $typeClassToAllTemplates Key = type class, value = array of templates
     * @return void
     */
    public abstract function beforePersistTemplateWithinPool($template, &$typeClassToAllTemplates);
    /**
     * After persisting all template instances within a pool.
     *
     * @param ServiceCloudConsumer[] $consumer
     * @param AbstractTemplate[] $typeClassToAllTemplates Key = type class, value = array of templates
     * @return void
     */
    public abstract function afterPersistTemplatesWithinPool($consumer, &$typeClassToAllTemplates);
}
