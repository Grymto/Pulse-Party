<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\translations;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractTemplateMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
use stdClass;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Template middleware that automatically creates a list of available translations to consumer data.
 * @internal
 */
abstract class TranslationsMiddleware extends AbstractTemplateMiddleware
{
    /**
     * Fetch available translations with the following array scheme:
     *
     * ```
     * [
     *      ['isUntranslated' => false, 'language' => 'de_DE', 'name' => 'German'],
     *      ...
     * ]
     * ```
     *
     * `name` can be optional.
     *
     * @param AbstractTemplate $template
     * @return array[]
     */
    public abstract function fetchTranslations($template);
    // Documented in AbstractTemplateMiddleware
    public function beforePersistTemplate($template, &$allTemplates)
    {
        // Silence is golden.
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeUsingTemplate($template)
    {
        $result = [];
        foreach ($this->fetchTranslations($template) as $translation) {
            $result[$translation['language']] = $translation;
        }
        if (\count($result) > 0) {
            $template->consumerData['translations'] = $result;
        }
    }
}
