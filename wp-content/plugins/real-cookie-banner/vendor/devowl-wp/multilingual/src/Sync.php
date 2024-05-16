<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

use WP_Error;
use WP_Post;
use WP_REST_Response;
use WP_Term;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Language specific action and filters to sync them language-wide. The earliest time
 * you can instance this class is `plugins_loaded`.
 * @internal
 */
class Sync
{
    private $enabled;
    private $posts;
    private $taxonomies;
    private $currentlySyncing = \false;
    /**
     * Abstract language implementation.
     *
     * @var AbstractSyncPlugin
     */
    private $compLanguage;
    /**
     * The last created term or post id.
     *
     * @var int|false
     */
    private $lastCreatedId = \false;
    /**
     * C'tor.
     *
     * @param array $posts
     * @param array $taxonomies
     * @param AbstractSyncPlugin $compLanguage
     * @codeCoverageIgnore
     */
    public function __construct($posts, $taxonomies, $compLanguage)
    {
        $this->posts = $posts;
        $this->taxonomies = $taxonomies;
        $this->compLanguage = $compLanguage;
        $this->hooks();
    }
    /**
     * Create sync hooks.
     */
    protected function hooks()
    {
        // Only do this for synced plugins
        if ($this->compLanguage instanceof AbstractSyncPlugin) {
            $this->enable();
            \add_action('rest_api_init', [$this, 'rest_api_init']);
        }
    }
    /**
     * Initialize `POST wp/v2/${taxonomy_or_post_type}/copy` REST API endpoints, which allows to
     * copy posts and taxonomies to other languages.
     */
    public function rest_api_init()
    {
        foreach ($this->getPostsConfiguration() as $postType => $configuration) {
            \register_rest_route('wp/v2', \sprintf('/%s/multilingual/copy', $postType, $configuration), ['methods' => 'POST', 'callback' => function ($request) use($postType, $configuration) {
                $compInstance = $this->compInstance();
                $id = $request->get_param('id');
                $targetLocale = $request->get_param('targetLocale');
                // Check if given ID exists as translation
                $translations = $compInstance->getPostTranslationIds($id, $postType);
                $translationIds = \array_values($translations);
                if (!\in_array($id, $translationIds, \true)) {
                    return new WP_Error('rest_multilingual_no_translation', 'The post id is not assigned to any translation within this post type.');
                }
                $sourceLocale = \array_search($id, $translationIds, \true);
                $sourceLocale = \array_keys($translations)[$sourceLocale];
                // Check if translation for this target already exists
                if (isset($translations[$targetLocale])) {
                    return new WP_Error('rest_multilingual_already_created', 'A translation for this target locale already exists.');
                }
                // Check if locale is valid
                if (!\in_array($targetLocale, $compInstance->getActiveLanguages(), \true)) {
                    return new WP_Error('rest_multilingual_invalid_target', 'The requested target locale does not exist.');
                }
                $created = $this->startCopyProcess()->copyPost($id, $sourceLocale, $targetLocale);
                if ($created === \false) {
                    return new WP_Error('rest_multilingual_failed', 'The copy could not be created.');
                }
                $translations[$targetLocale] = $created;
                // Read taxonomy entries for each translation
                $configTaxonomies = $configuration['taxonomies'] ?? [];
                foreach ($translations as $locale => &$postId) {
                    $row = ['id' => $postId, 'taxonomies' => []];
                    $compInstance->switchToLanguage($locale, function () use(&$row, $postId, $configTaxonomies) {
                        foreach ($configTaxonomies as $configTaxonomy) {
                            $row['taxonomies'][$configTaxonomy] = \wp_get_object_terms($postId, $configTaxonomy, ['fields' => 'ids', 'limit' => 0]);
                        }
                    });
                    $postId = $row;
                }
                return new WP_REST_Response(['sourceId' => $id, 'sourceLocale' => $sourceLocale, 'targetLocale' => $targetLocale, 'type' => $postType, 'translations' => $translations], 201);
            }, 'permission_callback' => function () use($postType) {
                return \current_user_can(\sprintf('publish_%ss', $postType));
            }, 'args' => ['id' => ['type' => 'integer', 'required' => \true], 'targetLocale' => ['required' => \true]]]);
        }
        foreach ($this->getTaxonomies() as $taxonomy => $configuration) {
            \register_rest_route('wp/v2', \sprintf('/%s/multilingual/copy', $taxonomy, $configuration), ['methods' => 'POST', 'callback' => function ($request) use($taxonomy, $configuration) {
                $compInstance = $this->compInstance();
                $id = $request->get_param('id');
                $targetLocale = $request->get_param('targetLocale');
                // Check if given ID exists as translation
                $translations = $compInstance->getTaxonomyTranslationIds($id, $taxonomy);
                $translationIds = \array_values($translations);
                if (!\in_array($id, $translationIds, \true)) {
                    return new WP_Error('rest_multilingual_no_translation', 'The term id is not assigned to any translation within this taxonomy.');
                }
                $sourceLocale = \array_search($id, $translationIds, \true);
                $sourceLocale = \array_keys($translations)[$sourceLocale];
                // Check if translation for this target already exists
                if (isset($translations[$targetLocale])) {
                    return new WP_Error('rest_multilingual_already_created', 'A translation for this target locale already exists.');
                }
                // Check if locale is valid
                if (!\in_array($targetLocale, $compInstance->getActiveLanguages(), \true)) {
                    return new WP_Error('rest_multilingual_invalid_target', 'The requested target locale does not exist.');
                }
                $created = $this->startCopyProcess()->copyTerm($id, $sourceLocale, $targetLocale);
                if ($created === \false) {
                    return new WP_Error('rest_multilingual_failed', 'The copy could not be created.');
                }
                $translations[$targetLocale] = $created;
                // Read taxonomy entries for each translation
                foreach ($translations as $locale => &$postId) {
                    $postId = ['id' => $postId];
                }
                return new WP_REST_Response(['sourceId' => $id, 'sourceLocale' => $sourceLocale, 'targetLocale' => $targetLocale, 'type' => $taxonomy, 'translations' => $translations], 201);
            }, 'permission_callback' => function ($request) use($taxonomy) {
                $term = \get_term($request->get_param('id'));
                if ($term instanceof WP_Term) {
                    $taxonomy = \get_taxonomy($term->taxonomy);
                    return \current_user_can($taxonomy->cap->manage_terms);
                }
                return \false;
            }, 'args' => ['id' => ['type' => 'integer', 'required' => \true], 'targetLocale' => ['required' => \true]]]);
        }
    }
    /**
     * Enable the sync mechanism.
     */
    public function enable()
    {
        $this->enabled = \true;
        \add_action('save_post', [$this, 'save_post'], 101, 3);
        // Priority 100 is used by WPML to create the `trid`
        \add_action('created_term', [$this, 'created_term'], 10, 3);
        \add_action('updated_term_meta', [$this, 'updated_term_meta'], 10, 4);
        \add_action('updated_postmeta', [$this, 'updated_postmeta'], 10, 4);
        $this->compLanguage->disableCopyAndSync($this);
    }
    /**
     * Disable the sync mechanism.
     */
    public function disable()
    {
        $this->enabled = \false;
        \remove_action('save_post', [$this, 'save_post'], 101, 3);
        \remove_action('created_term', [$this, 'created_term'], 10, 3);
        \remove_action('updated_term_meta', [$this, 'updated_term_meta'], 10, 4);
        \remove_action('updated_postmeta', [$this, 'updated_postmeta'], 10, 4);
    }
    /**
     * A post was saved, create duplicates for other languages, or sync meta and column if update.
     *
     * @param int $postId
     * @param WP_Post $post
     * @param boolean $update
     */
    public function save_post($postId, $post, $update)
    {
        // Determine if creation is possible
        $found = isset($this->posts[$post->post_type]) ? $this->posts[$post->post_type] : null;
        $this->lastCreatedId = \false;
        if ($found === null) {
            return;
        }
        $this->currentlySyncing = \true;
        // Assign newly created term to language
        $currentLanguage = $this->compInstance()->getCurrentLanguageFallback();
        if (!$update) {
            $this->compInstance()->setPostLanguage($postId, $currentLanguage);
        }
        // Collect all created translations of this post
        $translations = [];
        $translations[$currentLanguage] = $postId;
        // Temporarily deactivate this action to avoid recursion
        \remove_action('save_post', [$this, 'save_post'], 101, 3);
        $this->compInstance()->iterateOtherLanguagesContext(function ($locale, $current) use($postId, $post, $update, $found, &$translations) {
            if (!$update) {
                $created = $this->compInstance()->copyPostToOtherLanguage($locale, $current, $postId, \array_unique(\array_merge($found['meta']['copy-once'], $found['meta']['copy'])), isset($found['taxonomies']) ? $found['taxonomies'] : []);
                if ($created !== \false) {
                    $this->compInstance()->setPostLanguage($created, $locale);
                    $translations[$locale] = $created;
                    $this->lastCreatedId = $created;
                }
            } else {
                $translateId = $this->compInstance()->getCurrentPostId($postId, $post->post_type, $locale);
                if (isset($found['data']) && $translateId !== $postId) {
                    // Synchronize `wp_posts` columns
                    $argsToUpdate = ['ID' => $translateId];
                    foreach ($found['data'] as $column) {
                        $argsToUpdate[$column] = $post->{$column};
                    }
                    \wp_update_post($argsToUpdate);
                }
                if (isset($found['taxonomies'])) {
                    $this->compInstance()->copyPostTaxonomies($postId, $translateId, $found['taxonomies'], $locale);
                }
            }
        });
        if (!$update) {
            $this->compInstance()->postCopiedToAllOtherLanguages($translations);
        }
        \add_action('save_post', [$this, 'save_post'], 101, 3);
        $this->currentlySyncing = \false;
    }
    /**
     * A term in a taxonomy got created, create duplicates for each language.
     *
     * @param int $term_id
     * @param int $tt_id
     * @param string $taxonomy
     */
    public function created_term($term_id, $tt_id, $taxonomy)
    {
        // Determine if creation is possible
        $found = isset($this->taxonomies[$taxonomy]) ? $this->taxonomies[$taxonomy] : null;
        $this->lastCreatedId = \false;
        if ($found === null) {
            return;
        }
        $this->currentlySyncing = \true;
        // Assign newly created term to language
        $currentLanguage = $this->compInstance()->getCurrentLanguageFallback();
        $this->compInstance()->setTermLanguage($term_id, $currentLanguage);
        // Collect all created translations of this post
        $translations = [];
        $translations[$currentLanguage] = $term_id;
        // Temporarily deactivate this action to avoid recursion
        \remove_action('created_term', [$this, 'created_term'], 10, 3);
        $this->compInstance()->iterateOtherLanguagesContext(function ($locale, $current) use($term_id, $taxonomy, $found, &$translations) {
            $created = $this->compInstance()->copyTermToOtherLanguage($locale, $current, $term_id, $taxonomy, \array_unique(\array_merge($found['meta']['copy-once'], $found['meta']['copy'])));
            if ($created !== \false) {
                $this->compInstance()->setTermLanguage($created, $locale);
                $translations[$locale] = $created;
                $this->lastCreatedId = $created;
            }
        });
        $this->compInstance()->termCopiedToAllOtherLanguages($translations);
        \add_action('created_term', [$this, 'created_term'], 10, 3);
        $this->currentlySyncing = \false;
    }
    /**
     * Sync a set of copyable metas to other languages for defined taxonomies.
     *
     * @param int $meta_id ID of updated metadata entry.
     * @param int $term_id Term ID.
     * @param string $meta_key Metadata key.
     * @param mixed $meta_value Metadata value. This will be a PHP-serialized string representation of the value if the value is an array, an object, or itself a PHP-serialized string.
     */
    public function updated_term_meta($meta_id, $term_id, $meta_key, $meta_value)
    {
        // Determine if update possible
        $found = null;
        $taxonomy = \false;
        foreach ($this->taxonomies as $def_taxonomy => $def) {
            if (\get_term($term_id, $def_taxonomy) instanceof WP_Term && \in_array($meta_key, $def['meta']['copy'], \true)) {
                $found = $def;
                $taxonomy = $def_taxonomy;
                break;
            }
        }
        if ($found === null) {
            return;
        }
        $this->currentlySyncing = \true;
        // Temporarily disable this action to avoid recursion
        \remove_action('updated_term_meta', [$this, 'updated_term_meta'], 10, 4);
        $this->compInstance()->iterateOtherLanguagesContext(function ($locale, $currentLanguage) use($term_id, $taxonomy, $meta_key, $meta_value) {
            $toTermId = $this->compInstance()->getCurrentTermId($term_id, $taxonomy, $locale);
            \update_term_meta($toTermId, $meta_key, $this->compInstance()->filterMetaValue('term', $term_id, $toTermId, $meta_key, $meta_value, $locale));
        });
        \add_action('updated_term_meta', [$this, 'updated_term_meta'], 10, 4);
        $this->currentlySyncing = \false;
    }
    /**
     * Sync a set of copyable metas to other languages for defined post types.
     *
     * @param int $meta_id ID of updated metadata entry.
     * @param int $post_id Post ID.
     * @param string $meta_key Metadata key.
     * @param mixed $meta_value Metadata value. This will be a PHP-serialized string representation of the value if the value is an array, an object, or itself a PHP-serialized string.
     */
    public function updated_postmeta($meta_id, $post_id, $meta_key, $meta_value)
    {
        // Determine if update possible
        $post_type = \get_post_type($post_id);
        $found = isset($this->posts[$post_type]) ? $this->posts[$post_type] : null;
        if ($found === null || !\in_array($meta_key, $found['meta']['copy'], \true)) {
            return;
        }
        // Temporarily disable this action to avoid recursion
        \remove_action('updated_postmeta', [$this, 'updated_postmeta'], 10, 4);
        $this->compInstance()->iterateOtherLanguagesContext(function ($locale, $currentLanguage) use($post_id, $post_type, $meta_key, $meta_value) {
            $toPostId = $this->compInstance()->getCurrentPostId($post_id, $post_type, $locale);
            \update_post_meta($toPostId, $meta_key, $this->compInstance()->filterMetaValue('post', $post_id, $toPostId, $meta_key, $meta_value, $locale));
        });
        \add_action('updated_postmeta', [$this, 'updated_postmeta'], 10, 4);
    }
    /**
     * Create an instance of `CopyContent`.
     */
    public function startCopyProcess()
    {
        return new CopyContent($this);
    }
    /**
     * Get posts configuration passed via C'tor.
     *
     * @codeCoverageIgnore Getter
     */
    public function getPostsConfiguration()
    {
        return $this->posts;
    }
    /**
     * Get taxonomies configuration passed via C'tor.
     *
     * @codeCoverageIgnore Getter
     */
    public function getTaxonomies()
    {
        return $this->taxonomies;
    }
    /**
     * Is the syncing mechanism enabled?
     *
     * @return boolean
     * @codeCoverageIgnore Getter
     */
    public function isEnabled()
    {
        return $this->enabled;
    }
    /**
     * Is it currently syncing (e.g. copies a term or post to another language)?
     *
     * @return boolean
     * @codeCoverageIgnore Getter
     */
    public function isCurrentlySyncing()
    {
        return $this->currentlySyncing;
    }
    /**
     * Getter.
     */
    public function getLastCreatedId()
    {
        return $this->lastCreatedId;
    }
    /**
     * Get compatibility language instance.
     */
    public function compInstance()
    {
        return $this->compLanguage;
    }
}
