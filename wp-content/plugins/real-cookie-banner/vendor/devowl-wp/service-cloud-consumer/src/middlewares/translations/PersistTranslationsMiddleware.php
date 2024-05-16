<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\translations;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer\ServiceCloudConsumer;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractPoolMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\VarMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\Utils;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Pool middleware for both service and blocker templates to persist translations into a dedicated cache / database.
 * @internal
 */
abstract class PersistTranslationsMiddleware extends AbstractPoolMiddleware
{
    const PERSIST_CHUNK_SIZE = 20;
    /**
     * Never create translations for this string-values.
     */
    const EXPAND_SKIP_KEYS = ['language', 'identifier', 'successorOfIdentifierInfo', 'enabledWhenOneOf', 'recommendedWhenOneOf', 'extendsIdentifier', 'status', 'createdAt', 'tier', 'logoUrl', 'consumerData', 'serviceTemplateIdentifiers', 'ruleGroups', 'rules', 'ruleNotice', 'visualType', 'visualContentType', 'shouldUncheckContentBlockerCheckboxWhenOneOf', 'codeOptIn', 'codeOptOut', 'codeOnPageLoad', 'tagManagerOptInEventName', 'tagManagerOptOutEventName', 'dynamicFields', 'dataProcessingInCountries', 'dataProcessingInCountriesSpecialTreatments', 'legalBasis', 'legalBasisNotice', 'group', 'technicalHandlingNotice', 'createContentBlockerNotice', 'groupNotice', 'providerNotice'];
    /**
     * Fetch english templates as they should not be persisted to database.
     *
     * @param string[] $identifiers List of identifiers for which we want to the english templates
     * @param ServiceCloudConsumer $consumer
     */
    public abstract function fetchEnglishTemplates($identifiers, $consumer);
    /**
     * Persist translations to your database or object cache or whatever.
     *
     * @param Translation[] $translations
     * @param ServiceCloudConsumer $consumer
     */
    public abstract function persistTranslations($translations, $consumer);
    // Documented in AbstractPoolMiddleware
    public function beforePersistTemplateWithinPool($template, &$typeClassToAllTemplates)
    {
        // Silence is golden.
    }
    // Documented in AbstractPoolMiddleware
    public function afterPersistTemplatesWithinPool($consumers, &$typeClassToAllTemplates)
    {
        foreach ($consumers as $consumer) {
            $templates = \array_chunk($typeClassToAllTemplates[$consumer->getTypeClass()], self::PERSIST_CHUNK_SIZE);
            foreach ($templates as $chunk) {
                $t = $this->extractTranslations($chunk, $consumer);
                if (\count($t) > 0) {
                    $this->persistTranslations($t, $consumer);
                }
            }
        }
    }
    /**
     * Extract translation instances from a set of templates.
     *
     * @param AbstractTemplate[] $templates
     * @param ServiceCloudConsumer $consumer
     * @return Translation[]
     */
    public function extractTranslations($templates, $consumer)
    {
        if (\count($templates) === 0) {
            return [];
        }
        $translations = [];
        $varMiddleware = new VarMiddleware($this->getConsumer());
        // Read all english templates
        $englishTemplates = $this->fetchEnglishTemplates(\array_map(function ($template) {
            return $template->identifier;
        }, $templates), $consumer);
        // Map to object with identifier as key for performant access
        foreach ($englishTemplates as $template) {
            $englishTemplates[$template->identifier] = $template;
        }
        foreach ($templates as $translatedTemplate) {
            $englishTemplate = $englishTemplates[$translatedTemplate->identifier] ?? null;
            if ($englishTemplate === null || $translatedTemplate->language === $englishTemplate->language) {
                // en = en, ignore it, nothing to translate
                continue;
            }
            // Resolve variables, e.g. descriptions in content blockers which print out the admin email
            $varMiddleware->beforeUsingTemplate($englishTemplate);
            $varMiddleware->beforeUsingTemplate($translatedTemplate);
            $englishTemplateArr = AbstractTemplate::toArray($englishTemplate);
            $translatedTemplateArr = AbstractTemplate::toArray($translatedTemplate);
            $englishTemplateValues = Utils::expandKeys($englishTemplateArr, self::EXPAND_SKIP_KEYS);
            $translatedTemplateValues = Utils::expandKeys($translatedTemplateArr, self::EXPAND_SKIP_KEYS);
            foreach ($translatedTemplateValues as $key => &$translatedValue) {
                if (!\is_string($translatedValue) || empty($translatedValue)) {
                    continue;
                }
                $englishValue = $englishTemplateValues[$key] ?? null;
                if (empty($englishValue) || $translatedValue === $englishValue) {
                    continue;
                }
                $translations[] = new Translation($englishValue, $translatedValue);
            }
        }
        return $translations;
    }
}
