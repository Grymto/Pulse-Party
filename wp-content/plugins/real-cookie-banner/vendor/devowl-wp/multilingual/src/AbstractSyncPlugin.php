<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Utils;
use WP_Post;
use WP_Term;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * There are plugins like WPML or PolyLang which allows you to create a multilingual
 * WordPress installation. devowl.io plugins needs to be compatible with those plugins
 * so this abstract implementation handles actions like get original ID and get default language.
 * @internal
 */
abstract class AbstractSyncPlugin extends AbstractLanguagePlugin
{
    /**
     * `copyTermToOtherLanguage` and `copyPostToOtherLanguage` create a duplicate of a term and post. This map
     * holds the mapping of original-ID to translation-ID. This map is filled with the translations like this:
     *
     * ```
     * {
     *   "post": {
     *     "de": {
     *          800: 801
     *     }
     *   }
     * }
     * ```
     */
    protected $copyToOtherLanguageMap = ['post' => [], 'term' => []];
    // Documented in AbstractLanguagePlugin
    public function getSkipHTMLForTag($force = \false)
    {
        return '';
    }
    // Documented in AbstractLanguagePlugin
    public function maybePersistTranslation($sourceContent, $content, $sourceLocale, $targetLocale)
    {
        // Silence is golden.
    }
    // Documented in AbstractLanguagePlugin
    public function isCurrentlyInEditorPreview()
    {
        return \false;
    }
    // Documented in AbstractLanguagePlugin
    public function translateStrings(&$content, $locale, $context = null)
    {
        return $content;
    }
    /**
     * This method is called due to `Sync::created_term`. It allows you to get a list of all
     * translations of a term in an associate array.
     *
     * @param int[] $translations
     */
    public function termCopiedToAllOtherLanguages($translations)
    {
        // Silence is golden.
    }
    /**
     * This method is called due to `Sync::save_post`. It allows you to get a list of all
     * translations of a post in an associate array.
     *
     * @param int[] $translations
     */
    public function postCopiedToAllOtherLanguages($translations)
    {
        // Silence is golden.
    }
    /**
     * This method is called due to `Sync::created_term`. It allows you to assign a term to a given language.
     *
     * @param int $termId
     * @param string $locale
     */
    public function setTermLanguage($termId, $locale)
    {
        // Silence is golden.
    }
    /**
     * This method is called due to `Sync::save_post`. It allows you to assign a post to a given language.
     *
     * @param int $postId
     * @param string $locale
     */
    public function setPostLanguage($postId, $locale)
    {
        // Silence is golden.
    }
    /**
     * Translate given term to other language.
     *
     * @param string $locale
     * @param string $currentLanguage
     * @param int $term_id
     * @param string $taxonomy
     * @param string[] $meta The meta keys to copy
     * @return int|boolean The new created term id
     */
    public function copyTermToOtherLanguage($locale, $currentLanguage, $term_id, $taxonomy, $meta)
    {
        // Read term
        $term = \get_term($term_id);
        // Create
        $createdTermId = $this->duplicateTerm($term, $taxonomy);
        if (\is_wp_error($createdTermId)) {
            return \false;
        }
        $this->copyToOtherLanguageMap['term'][$locale] = $this->copyToOtherLanguageMap['term'][$locale] ?? [];
        $this->copyToOtherLanguageMap['term'][$locale][$term_id] = $createdTermId;
        $this->duplicateTermMeta($term_id, $createdTermId, $meta);
        return $createdTermId;
    }
    /**
     * Translate given post to other language.
     *
     * @param string $locale
     * @param string $currentLanguage
     * @param string $post_id
     * @param string[] $meta The meta keys to copy
     * @param string[] $taxonomies The taxonomies to copy
     * @return int|boolean The new created post id
     */
    public function copyPostToOtherLanguage($locale, $currentLanguage, $post_id, $meta, $taxonomies)
    {
        // Read post
        $post = \get_post($post_id);
        // Create
        $created = $this->duplicatePost($post);
        if ($created === 0) {
            return \false;
        }
        $this->copyToOtherLanguageMap['post'][$locale] = $this->copyToOtherLanguageMap['post'][$locale] ?? [];
        $this->copyToOtherLanguageMap['post'][$locale][$post_id] = $created;
        $this->copyPostTaxonomies($post_id, $created, $taxonomies, $locale);
        $this->duplicatePostMeta($post_id, $created, $meta);
        return $created;
    }
    /**
     * A simple `get_term` => `wp_insert_term` wrapper.
     *
     * @param WP_Term $term
     * @param string $taxonomy
     */
    protected function duplicateTerm($term, $taxonomy)
    {
        // Translate name and description
        list(, $name) = $this->translateInput($term->name);
        list(, $description) = $this->translateInput($term->description);
        // Create
        $created = \wp_insert_term($name, $taxonomy, [
            'slug' => $name . '-' . $this->getCurrentLanguageFallback(),
            // E. g. PolyLang reports "Term already exists"
            'description' => $description,
        ]);
        return \is_wp_error($created) ? $created : $created['term_id'];
    }
    /**
     * A simple `get_post` => `wp_insert_post` wrapper.
     *
     * @param WP_Post $post
     */
    protected function duplicatePost($post)
    {
        // Translate name and description
        $args = ['post_title' => $this->translateInput($post->post_title)[1], 'post_content' => $this->translateInput($post->post_content)[1], 'post_status' => $post->post_status, 'post_type' => $post->post_type, 'menu_order' => $post->menu_order];
        // Create
        return \wp_insert_post($args);
    }
    /**
     * Listen to term meta addition and copy.
     *
     * @param int $from
     * @param int $to
     * @param string[] $meta Meta keys to copy
     */
    protected function duplicateTermMeta($from, $to, $meta)
    {
        $this->duplicateMeta('term', $from, $to, $meta);
    }
    /**
     * Copy already existing meta as it can be inserted with `meta_input` directly.
     * Additionally listen to term meta addition and copy.
     *
     * @param int $from
     * @param int $to
     * @param string[] $meta Meta keys to copy
     */
    public function duplicatePostMeta($from, $to, $meta)
    {
        $customMeta = \get_post_custom($from);
        foreach ($customMeta as $key => $values) {
            if (\in_array($key, $meta, \true)) {
                foreach ($values as $value) {
                    \add_post_meta($to, $key, $this->filterMetaValue('post', $from, $to, $key, $value, $this->getCurrentLanguageFallback()));
                }
            }
        }
        $this->duplicateMeta('post', $from, $to, $meta);
    }
    /**
     * Listen to meta (term, post, ...) addition and copy.
     *
     * @param string $type E. g. 'post'
     * @param int $from
     * @param int $to
     * @param string[] $meta Meta keys to copy
     */
    protected function duplicateMeta($type, $from, $to, $meta)
    {
        $locale = $this->getCurrentLanguageFallback();
        // Temporarily save the current translations so it can be used "later" when we call the action
        $currentTranslationEntries = $this->currentTranslationEntries;
        \add_action('added_' . $type . '_meta', function ($mid, $object_id, $meta_key, $_meta_value) use($from, $to, $meta, $type, $locale, $currentTranslationEntries) {
            if ($object_id === $from && \in_array($meta_key, $meta, \true)) {
                // Restore our previous locale because we are outside our sync mechanism (caused by `add_action`)
                ($previousCurrentTranslationEntries =& $this->currentTranslationEntries) ?? null;
                $this->currentTranslationEntries =& $currentTranslationEntries;
                $this->switchToLanguage($locale, function () use($type, $from, $to, $meta_key, $_meta_value, $locale) {
                    \call_user_func('add_' . $type . '_meta', $to, $meta_key, $this->filterMetaValue($type, $from, $to, $meta_key, $_meta_value, $locale));
                });
                $this->currentTranslationEntries = $previousCurrentTranslationEntries;
            }
        }, 10, 4);
    }
    /**
     * Copy already existing taxonomies as it can be inserted with `tax_input` directly.
     * Additionally listen to term additions and copy.
     *
     * @param int $from
     * @param int $to
     * @param string[] $taxonomies Taxonomy keys to copy
     * @param string $locale The destination locale
     */
    public function copyPostTaxonomies($from, $to, $taxonomies, $locale)
    {
        $doCopy = function ($post_id, $terms, $taxonomy) use($locale) {
            $copyIds = [];
            foreach ($terms as $term) {
                // Get category id of the original term id for the other language
                $newTermId = $this->getCurrentTermId(\get_term($term)->term_id, $taxonomy, $locale);
                if ($newTermId > 0) {
                    $copyIds[] = \intval($newTermId);
                }
            }
            // Avoid that e.g. PolyLang sets the term id of the current language
            Utils::withoutFilters('set_object_terms', function () use($post_id, $copyIds, $taxonomy) {
                \wp_set_object_terms($post_id, $copyIds, $taxonomy);
            });
        };
        foreach ($taxonomies as $taxonomy) {
            $originalTerms = \get_the_terms($from, $taxonomy);
            if (\is_array($originalTerms)) {
                $doCopy($to, $originalTerms, $taxonomy);
            }
        }
        \add_action('set_object_terms', function ($object_id, $terms, $tt_ids, $taxonomy, $append) use($from, $to, $taxonomies, $doCopy) {
            if ($object_id === $from && \in_array($taxonomy, $taxonomies, \true) && $from !== $to) {
                $doCopy($to, $terms, $taxonomy);
            }
        }, 10, 5);
    }
    /**
     * Apply a WordPress filter so a meta value can be modified for copy process
     * to other languages.
     *
     * @param string $type E. g. 'post'
     * @param int $from Object id of source language item
     * @param int $to Object id of destination language item
     * @param string $meta_key
     * @param mixed $meta_value
     * @param string $locale Destination locale
     */
    public function filterMetaValue($type, $from, $to, $meta_key, $meta_value, $locale)
    {
        // See https://developer.wordpress.org/reference/functions/update_post_meta/#workaround
        if (Utils::isJson($meta_value)) {
            $meta_value = \wp_slash($meta_value);
        }
        /**
         * Allows to modify a meta value when it gets copied to another language.
         *
         * @hook DevOwl/Multilingual/Copy/Meta/$type/$meta_key
         * @param {mixed} $meta_value
         * @param {int} $from Object id of source language item
         * @param {int} $to Object id of destination language item
         * @param string $locale Destination locale
         * @param string $meta_key
         * @return {mixed}
         */
        return \apply_filters('DevOwl/Multilingual/Copy/Meta/' . $type . '/' . $meta_key, $meta_value, $from, $to, $locale, $meta_key);
    }
}
