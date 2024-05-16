<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\blocker;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractTemplateMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\BlockerTemplate;
/**
 * Middleware that provides button text for specific content types.
 * @internal
 */
class ContentTypeButtonTextMiddleware extends AbstractTemplateMiddleware
{
    // Documented in AbstractTemplateMiddleware
    public function beforePersistTemplate($template, &$allTemplates)
    {
        if ($template instanceof BlockerTemplate) {
            $variableResolver = $this->getVariableResolver();
            $buttonText = $variableResolver->resolveDefault('i18n.ContentTypeButtonTextMiddleware.loadContent', 'Load content');
            $contentType = $template->visualContentType;
            if (\in_array($contentType, ['video-player', 'audio-player'], \true)) {
                $buttonText = '';
            } elseif ($contentType === 'map') {
                $buttonText = $variableResolver->resolveDefault('i18n.ContentTypeButtonTextMiddleware.loadMap', 'Load map');
            } elseif ($contentType === 'generic' && \stripos($template->identifier, 'form') !== \false) {
                $buttonText = $variableResolver->resolveDefault('i18n.ContentTypeButtonTextMiddleware.loadForm', 'Load form');
            }
            if (!empty($buttonText)) {
                $template->visualHeroButtonText = $buttonText;
            }
        }
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeUsingTemplate($template)
    {
        // Silence is golden.
    }
}
