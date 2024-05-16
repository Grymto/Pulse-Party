<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\Utils;
/**
 * Middleware to identify successors of templates and save relevant data to the consumer data.
 *
 * The existing `AbtractTemplate` instances from the variable resolver `created.global.{TemplateClass::class}` need to have a `consumerData['id']` property.
 * @internal
 */
class SuccessorMiddleware extends AbstractTemplateMiddleware
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
        /**
         * Templates.
         *
         * @var AbstractTemplate[]
         */
        $existing = $variableResolver->resolveRequired('created.global.' . \get_class($template));
        $result = [];
        foreach ($template->successorOfIdentifierInfo as $row) {
            $exists = Utils::in_array_column($existing, 'identifier', $row['identifier'], \true);
            if ($exists) {
                $result[] = \array_merge($row, ['id' => $exists->consumerData['id']]);
            }
        }
        $template->consumerData['successorOf'] = $result;
    }
}
