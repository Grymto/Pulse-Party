<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\services;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractTemplateMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\ServiceTemplate;
/**
 * Translate the group to a human readable string.
 *
 * See also `api-packages/api-real-cookie-banner/src/entity/template/service/service.ts` enum `EServiceTemplateGroup`.
 * @internal
 */
class GroupMiddleware extends AbstractTemplateMiddleware
{
    // Documented in AbstractTemplateMiddleware
    public function beforePersistTemplate($template, &$allTemplates)
    {
        // Silence is golden.
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeUsingTemplate($template)
    {
        if ($template instanceof ServiceTemplate && \is_string($template->group)) {
            $template->group = $this->getVariableResolver()->resolveDefault(\sprintf('i18n.GroupMiddleware.group.%s', $template->group), \ucfirst($template->group));
        }
    }
}
