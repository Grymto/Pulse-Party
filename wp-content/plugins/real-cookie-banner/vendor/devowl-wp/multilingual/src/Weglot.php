<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

use Exception;
use WeglotWP\Services\Language_Service_Weglot;
use WeglotWP\Services\Translate_Service_Weglot;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Weglot Output Buffering compatibility.
 *
 * @see https://weglot.com/
 * @internal
 */
class Weglot extends AbstractOutputBufferPlugin
{
    // Documented in AbstractOutputBufferPlugin
    public function maybePersistTranslation($sourceContent, $content, $sourceLocale, $targetLocale)
    {
        // TODO how can we persist known translations to Weglot?
    }
    // Documented in AbstractSyncPlugin
    public function switch($locale)
    {
        $languageEntry = $this->getLanguageService()->get_language_from_internal($locale);
        $this->getTranslateService()->set_current_language($languageEntry);
    }
    // Documented in AbstractLanguagePlugin
    public function getActiveLanguages()
    {
        $result = [$this->getDefaultLanguage()];
        foreach (\weglot_get_destination_languages() as $lang) {
            $result[] = $lang['language_to'];
        }
        return $result;
    }
    // Documented in AbstractLanguagePlugin
    public function getLanguageSwitcher()
    {
        $result = [];
        try {
            // See https://developers.weglot.com/wordpress/use-cases/language-selector-styling#example-rebuild-the-language-selector-from-scratch
            $current_this = \weglot_get_service('Button_Service_Weglot');
            $option_service = \weglot_get_service('Option_Service_Weglot');
            $request_url_service = \weglot_get_service('Request_Url_Service_Weglot');
            $language_service = \weglot_get_service('Language_Service_Weglot');
            if (\apply_filters('weglot_view_button_html', !$request_url_service->is_eligible_url())) {
                return $result;
            }
            $original_language = \weglot_get_original_language();
            $destination_language = \weglot_get_destination_languages();
            $current_language = $request_url_service->get_current_language(\false);
            if (!empty($original_language) && !empty($destination_language)) {
                $current_language_entry = $language_service->get_language_from_internal($current_language->getInternalCode());
                $name = $current_this->get_name_with_language_entry($current_language_entry);
                foreach ($language_service->get_original_and_destination_languages($request_url_service->is_allowed_private()) as $language) {
                    $link_button = $request_url_service->get_weglot_url()->getForLanguage($language);
                    if (!$link_button) {
                        continue;
                    }
                    $name = $current_this->get_name_with_language_entry($language);
                    if ($language === $language_service->get_original_language() && \strpos($link_button, 'no_lredirect') === \false && (\is_home() || \is_front_page()) && $option_service->get_option('auto_redirect')) {
                        // Only for homepage
                        if (\strpos($link_button, '?') !== \false) {
                            $link_button = \str_replace('?', '?no_lredirect=true', $link_button);
                        } else {
                            $link_button .= '?no_lredirect=true';
                        }
                    }
                    $result[] = [
                        'name' => $name,
                        'current' => $language->getInternalCode() === $current_language->getInternalCode(),
                        // Weglot uses CSS classes with spread image
                        //'flag' => '',
                        'url' => $link_button,
                        'locale' => $language->getInternalCode(),
                    ];
                }
            }
        } catch (Exception $e) {
            // Silence is golden.
        }
        return $result;
    }
    // Documented in AbstractLanguagePlugin
    public function getTranslatedName($locale)
    {
        $lang = $this->getLanguageService()->get_language_from_internal($locale);
        return isset($lang) ? $lang->getEnglishName() : $lang;
    }
    // Documented in AbstractLanguagePlugin
    public function getCountryFlag($locale)
    {
        // Weglot uses CSS classes with spread image
        return \false;
    }
    // Documented in AbstractLanguagePlugin
    public function getPermalink($url, $locale)
    {
        $object = \weglot_create_url_object($url);
        foreach ($object->getAllUrls() as $foundUrl) {
            if ($foundUrl['language']->getInternalCode() === $locale) {
                return $foundUrl['url'];
            }
        }
        return $url;
    }
    // Documented in AbstractLanguagePlugin
    public function getWordPressCompatibleLanguageCode($locale)
    {
        return IsoCodeMapper::twoToWordPressCompatible($locale);
    }
    // Documented in AbstractLanguagePlugin
    public function getDefaultLanguage()
    {
        return \weglot_get_original_language();
    }
    // Documented in AbstractLanguagePlugin
    public function getCurrentLanguage()
    {
        return \weglot_get_current_language();
    }
    // Documented in AbstractOutputBufferPlugin
    public function getSkipHTMLForTag($force = \false)
    {
        // See https://developers.weglot.com/wordpress/getting-started#exclude-blocks
        return $this->isCurrentlyInEditorPreview() && !$force ? '' : 'data-wg-notranslate';
    }
    // Documented in AbstractOutputBufferPlugin
    public function isCurrentlyInEditorPreview()
    {
        return isset($_GET['weglot-ve']);
    }
    // Documented in AbstractOutputBufferPlugin
    public function translateStrings(&$content, $locale, $context = null)
    {
        if (!$this->isCurrentlyInEditorPreview()) {
            $currentLanguage = $this->getCurrentLanguage();
            if ($locale !== null) {
                $this->switch($locale);
            }
            $result = $this->wrapHtmlToArray($this->getTranslateService()->weglot_treat_page($this->wrapArrayToHtml($content)));
            $this->remapResultToReference($content, $result, $locale, $context);
            if ($locale !== null) {
                $this->switch($currentLanguage);
            }
        }
    }
    /**
     * Get the `Language_Service_Weglot` instance.
     *
     * @return Language_Service_Weglot
     */
    protected function getLanguageService()
    {
        return \weglot_get_service('Language_Service_Weglot');
    }
    /**
     * Get the `Translate_Service_Weglot` instance.
     *
     * @return Translate_Service_Weglot
     */
    protected function getTranslateService()
    {
        return \weglot_get_service('Translate_Service_Weglot');
    }
    /**
     * Check if Weglot is active.
     */
    public static function isPresent()
    {
        return \is_plugin_active('weglot/weglot.php');
    }
}
