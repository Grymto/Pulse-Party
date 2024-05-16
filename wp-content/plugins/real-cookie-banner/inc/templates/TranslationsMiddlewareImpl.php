<?php

namespace DevOwl\RealCookieBanner\templates;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\translations\TranslationsMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\ServiceTemplate;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * See `TranslationsMiddleware`.
 * @internal
 */
class TranslationsMiddlewareImpl extends TranslationsMiddleware
{
    use UtilsProvider;
    // Documented in TranslationsMiddleware
    public function fetchTranslations($template)
    {
        global $wpdb;
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\templates\StorageHelper::TABLE_NAME);
        $compLanguage = Core::getInstance()->getCompLanguage();
        $activeLanguages = $compLanguage->getActiveLanguages();
        if (\count($activeLanguages) === 0) {
            $activeLanguages[] = \DevOwl\RealCookieBanner\templates\TemplateConsumers::getContext();
        }
        // Map of WP compatible language code to multilingual plugin code
        $activeLanguagesMap = [];
        foreach ($activeLanguages as $a) {
            $activeLanguagesMap[$compLanguage->getWordPressCompatibleLanguageCode($a)] = $a;
        }
        // phpcs:disable WordPress.DB
        $result = $wpdb->get_results($wpdb->prepare("SELECT context AS `language`, is_untranslated AS isUntranslated FROM {$table_name} WHERE identifier = %s AND type = %s", $template->identifier, $template instanceof ServiceTemplate ? 'service' : 'blocker'), ARRAY_A);
        // phpcs:enable WordPress.DB
        foreach ($result as $key => &$row) {
            if (!\in_array($row['language'], \array_keys($activeLanguagesMap), \true)) {
                unset($result[$key]);
                continue;
            }
            $row['isUntranslated'] = \boolval($row['isUntranslated']);
            $useLanguageCode = $activeLanguagesMap[$row['language']] ?? $row['language'];
            $translatedName = $compLanguage->getTranslatedName($useLanguageCode);
            if ($row['language'] !== $translatedName) {
                $row['name'] = $translatedName;
            }
        }
        return $result;
    }
}
