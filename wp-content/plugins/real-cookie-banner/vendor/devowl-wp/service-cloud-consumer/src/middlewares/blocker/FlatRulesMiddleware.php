<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\blocker;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractTemplateMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\BlockerTemplate;
/**
 * Middleware that transforms all `rules` expressions into an unique, flat consumer data
 * so it an be used e.g. in `@devowl-wp/react-cookie-banner-admin` form.
 * @internal
 */
class FlatRulesMiddleware extends AbstractTemplateMiddleware
{
    // Documented in AbstractTemplateMiddleware
    public function beforePersistTemplate($template, &$allTemplates)
    {
        // Silence is golden.
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeUsingTemplate($template)
    {
        if ($template instanceof BlockerTemplate) {
            $rules = [];
            foreach ($template->rules as $rule) {
                $rules[] = $rule['expression'];
            }
            $template->consumerData['rules'] = \array_values(\array_unique($rules));
        }
    }
}
