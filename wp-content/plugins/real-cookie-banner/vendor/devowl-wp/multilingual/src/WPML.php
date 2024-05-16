<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * WPML language handler.
 * @internal
 */
class WPML extends AbstractSyncPlugin
{
    // Documented in AbstractSyncPlugin
    public function switch($locale)
    {
        global $sitepress;
        $sitepress->switch_lang($locale);
    }
    // Documented in AbstractSyncPlugin
    public function copyTermToOtherLanguage($locale, $currentLanguage, $term_id, $taxonomy, $meta)
    {
        global $sitepress;
        $createdTermId = parent::copyTermToOtherLanguage($locale, $currentLanguage, $term_id, $taxonomy, $meta);
        if (!$createdTermId) {
            return \false;
        }
        // Create term translation (https://wordpress.stackexchange.com/a/309046/83335)
        $originalElementId = \get_term($term_id)->term_taxonomy_id;
        $createdElementId = \get_term($createdTermId)->term_taxonomy_id;
        $trid = $sitepress->get_element_trid($originalElementId, 'tax_' . $taxonomy);
        $sitepress->set_element_language_details($createdElementId, 'tax_' . $taxonomy, $trid, $locale, $this->getSourceLanguage($term_id, $taxonomy, $currentLanguage));
        return $createdTermId;
    }
    // Documented in AbstractSyncPlugin
    public function copyPostToOtherLanguage($locale, $currentLanguage, $post_id, $meta, $taxonomies)
    {
        global $sitepress;
        // Read post
        $post = \get_post($post_id);
        // Create
        $created = parent::copyPostToOtherLanguage($locale, $currentLanguage, $post_id, $meta, $taxonomies);
        if (!$created) {
            return \false;
        }
        // WPML has an issue that the `trid` is not yet known directly in `save_post` (https://wpml.org/forums/topic/get_element_trid-returns-null/)
        // So, the posts are linked through the `shutdown` action.
        \add_action('shutdown', function () use($sitepress, $post, $post_id, $created, $locale, $currentLanguage) {
            $trid = $sitepress->get_element_trid($post_id, 'post_' . $post->post_type);
            $sitepress->set_element_language_details($created, 'post_' . $post->post_type, $trid, $locale, $this->getSourceLanguage($post_id, $post->post_type, $currentLanguage));
        });
        return $created;
    }
    // Documented in AbstractLanguagePlugin
    public function getActiveLanguages()
    {
        return \array_keys(\apply_filters('wpml_active_languages', []));
    }
    // Documented in AbstractLanguagePlugin
    public function getLanguageSwitcher()
    {
        $result = [];
        foreach (\apply_filters('wpml_active_languages', [], ['skip_missing' => 0]) as $row) {
            $result[] = ['name' => $row['native_name'], 'current' => \boolval($row['active'] ?? \false), 'flag' => $row['country_flag_url'] ?? '', 'url' => $row['url'], 'locale' => $row['language_code']];
        }
        return $result;
    }
    // Documented in AbstractLanguagePlugin
    public function getTranslatedName($locale)
    {
        $activeLanguages = \apply_filters('wpml_active_languages', []);
        return isset($activeLanguages[$locale]) ? $activeLanguages[$locale]['translated_name'] : $locale;
    }
    // Documented in AbstractLanguagePlugin
    public function getCountryFlag($locale)
    {
        $settings = \apply_filters('wpml_active_languages', []);
        if (isset($settings[$locale])) {
            return $settings[$locale]['country_flag_url'] ?? \false;
        }
        return \false;
    }
    // Documented in AbstractLanguagePlugin
    public function getPermalink($url, $locale)
    {
        return \apply_filters('wpml_permalink', $url, $locale, \true);
    }
    // Documented in AbstractLanguagePlugin
    public function getWordPressCompatibleLanguageCode($locale)
    {
        $objects = \apply_filters('wpml_active_languages', []);
        return isset($objects[$locale]) ? $objects[$locale]['default_locale'] : $locale;
    }
    // Documented in AbstractLanguagePlugin
    public function getDefaultLanguage()
    {
        global $sitepress;
        return $sitepress->get_default_language();
    }
    // Documented in AbstractLanguagePlugin
    public function getCurrentLanguage()
    {
        global $sitepress;
        return $sitepress->get_current_language();
    }
    // Documented in AbstractSyncPlugin
    public function getOriginalPostId($id, $post_type)
    {
        return \intval(\icl_object_id($id, $post_type, \false, $this->getDefaultLanguage()));
    }
    // Documented in AbstractSyncPlugin
    public function getOriginalTermId($id, $taxonomy)
    {
        return \intval(\icl_object_id($id, $taxonomy, \false, $this->getDefaultLanguage()));
    }
    // Documented in AbstractLanguagePlugin
    public function getPostTranslationIds($id, $post_type)
    {
        $result = [];
        $trid = \apply_filters('wpml_element_trid', null, $id, 'post_' . $post_type);
        $translations = \apply_filters('wpml_get_element_translations', null, $trid, 'post_' . $post_type);
        if (\is_array($translations)) {
            foreach ($translations as $translation) {
                $result[$translation->language_code] = \intval($translation->element_id);
            }
        }
        return $result;
    }
    // Documented in AbstractLanguagePlugin
    public function getTaxonomyTranslationIds($id, $taxonomy)
    {
        $result = [];
        $trid = \apply_filters('wpml_element_trid', null, $id, 'tax_' . $taxonomy);
        $translations = \apply_filters('wpml_get_element_translations', null, $trid, 'tax_' . $taxonomy);
        if (\is_array($translations)) {
            foreach ($translations as $translation) {
                $result[$translation->language_code] = \intval($translation->element_id);
            }
        }
        return $result;
    }
    // Documented in AbstractSyncPlugin
    public function getCurrentPostId($id, $post_type, $locale = null)
    {
        if ($locale !== null && isset($this->copyToOtherLanguageMap['post'][$locale])) {
            $map = $this->copyToOtherLanguageMap['post'][$locale];
            if (isset($map[$id])) {
                return $map[$id];
            }
        }
        return \intval(\icl_object_id($id, $post_type, \true, $locale === null ? $this->getCurrentLanguage() : $locale));
    }
    // Documented in AbstractSyncPlugin
    public function getCurrentTermId($id, $taxonomy, $locale = null)
    {
        if ($locale !== null && isset($this->copyToOtherLanguageMap['term'][$locale])) {
            $map = $this->copyToOtherLanguageMap['term'][$locale];
            if (isset($map[$id])) {
                return $map[$id];
            }
        }
        return \intval(\icl_object_id($id, $taxonomy, \true, $locale === null ? $this->getCurrentLanguage() : $locale));
    }
    /**
     * Get the source language of a given element id and element type.
     *
     * @param int $element_id
     * @param string $element_type
     * @param mixed $fallback
     */
    protected function getSourceLanguage($element_id, $element_type, $fallback = null)
    {
        $details = \apply_filters('wpml_element_language_details', null, ['element_id' => $element_id, 'element_type' => $element_type]);
        return \is_array($details) && isset($details['source_language_code']) ? isset($details['source_language_code']) : $fallback;
    }
    // Documented in AbstractLanguagePlugin
    public function disableCopyAndSync($sync)
    {
        // WPML does not support any copy and sync feature
    }
    /**
     * Check if WPML is active.
     */
    public static function isPresent()
    {
        return \is_plugin_active('sitepress-multilingual-cms/sitepress.php');
    }
}
