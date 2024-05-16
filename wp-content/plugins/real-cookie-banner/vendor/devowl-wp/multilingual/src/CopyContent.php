<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

use WP_Term;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Allows to copy content by sync options or by custom callback.
 * @internal
 */
class CopyContent
{
    /**
     * Sync mechanism class.
     *
     * @var Sync
     */
    private $sync;
    /**
     * C'tor.
     *
     * @param Sync $sync
     * @codeCoverageIgnore
     */
    public function __construct($sync)
    {
        $this->sync = $sync;
    }
    /**
     * The content will be copied from your sync options you passed via constructor.
     *
     * @param string $sourceLanguage
     * @param string[] $destinationLanguages
     */
    public function copyAll($sourceLanguage, $destinationLanguages)
    {
        $this->copy($sourceLanguage, $destinationLanguages, [$this, 'fromSyncOptions']);
    }
    /**
     * The given post ID will be copied from your sync options you passed via constructor.
     *
     * @param int $id
     * @param string $sourceLanguage
     * @param string $destinationLanguage
     */
    public function copyPost($id, $sourceLanguage, $destinationLanguage)
    {
        $postType = \get_post_type($id);
        if (!$postType) {
            return \false;
        }
        $postConfiguration = $this->getSync()->getPostsConfiguration()[$postType] ?? null;
        if ($postConfiguration === null) {
            return \false;
        }
        $this->copy($sourceLanguage, [$destinationLanguage], function () use($id, $postType, $postConfiguration) {
            $this->fromPostType($postType, $postConfiguration, [$id]);
        });
        return $this->getSync()->getLastCreatedId();
    }
    /**
     * The given term ID will be copied from your sync options you passed via constructor.
     *
     * @param int $id
     * @param string $sourceLanguage
     * @param string $destinationLanguage
     */
    public function copyTerm($id, $sourceLanguage, $destinationLanguage)
    {
        $term = \get_term($id);
        if (!$term instanceof WP_Term) {
            return \false;
        }
        $termConfiguration = $this->getSync()->getTaxonomies()[$term->taxonomy] ?? null;
        if ($termConfiguration === null) {
            return \false;
        }
        $this->copy($sourceLanguage, [$destinationLanguage], function () use($id, $term, $termConfiguration) {
            $this->fromTaxonomy($term->taxonomy, $termConfiguration, [$id]);
        });
        return $this->getSync()->getLastCreatedId();
    }
    /**
     * Copy content to a set of other languages. If you do not pass a callback the content
     * will be copied from your sync options you passed via constructor.
     *
     * @param string $sourceLanguage
     * @param string[] $destinationLanguages
     * @param callable $callback Your callback which starts copying in the correct language context
     */
    public function copy($sourceLanguage, $destinationLanguages, $callback)
    {
        $compLanguage = $this->compInstance();
        // Avoid to copy to already existing languages
        $skip = \array_values(\array_diff($compLanguage->getActiveLanguages(), $destinationLanguages));
        foreach ($skip as $skipLocale) {
            \add_filter('DevOwl/Multilingual/IterateLanguageContexts/Skip/' . $skipLocale, '__return_true');
        }
        $compLanguage->switchToLanguage($sourceLanguage, function () use($compLanguage, $callback) {
            // Snapshot our content's language and tear down the text domain so the newly
            // created terms and posts gets the translations correctly
            $compLanguage->snapshotCurrentTranslations();
            $compLanguage->lockCurrentTranslations(\true);
            $compLanguage->teardownTemporaryTextDomain();
            \call_user_func($callback);
            $compLanguage->lockCurrentTranslations(\false);
            $compLanguage->unsetCurrentTranslations();
        });
        foreach ($skip as $skipLocale) {
            \remove_filter('DevOwl/Multilingual/IterateLanguageContexts/Skip/' . $skipLocale, '__return_true');
        }
        return \true;
    }
    /**
     * Copy the complete content from our sync options. Use this only inside your `copyContentTo` callback!
     */
    public function fromSyncOptions()
    {
        foreach ($this->getSync()->getTaxonomies() as $taxonomy => $syncOptions) {
            $this->fromTaxonomy($taxonomy, $syncOptions);
        }
        foreach ($this->getSync()->getPostsConfiguration() as $post_type => $syncOptions) {
            $this->fromPostType($post_type, $syncOptions);
        }
    }
    /**
     * Copy content from a given taxonomy and sync options. Use this only inside your `copyContentTo` callback!
     *
     * @param string $taxonomy
     * @param array $syncOptions
     * @param int[] $termIds
     */
    public function fromTaxonomy($taxonomy, $syncOptions, $termIds = null)
    {
        $termIds = $termIds === null ? \get_terms(['taxonomy' => $taxonomy, 'hide_empty' => \false, 'fields' => 'ids', 'lang' => $this->compInstance()->getCurrentLanguage()]) : $termIds;
        foreach ($termIds as $term_id) {
            $this->getSync()->created_term($term_id, null, $taxonomy);
            foreach ($syncOptions['meta']['copy-once'] as $meta) {
                $this->getSync()->updated_term_meta(null, $term_id, $meta, \get_term_meta($term_id, $meta, \true));
            }
        }
    }
    /**
     * Copy content from a given post type and sync options. Use this only inside your `copyContentTo` callback!
     *
     * @param string $post_type
     * @param array $syncOptions
     * @param int[] $postIds
     */
    public function fromPostType($post_type, $syncOptions, $postIds = null)
    {
        $postIds = $postIds === null ? \get_posts(['post_type' => $post_type, 'numberposts' => -1, 'nopaging' => \true, 'lang' => $this->compInstance()->getCurrentLanguage(), 'fields' => 'ids', 'suppress_filters' => \false]) : $postIds;
        foreach ($postIds as $post_id) {
            $this->getSync()->save_post($post_id, \get_post($post_id), \false);
            foreach ($syncOptions['meta']['copy-once'] as $meta) {
                $this->getSync()->updated_postmeta(null, $post_id, $meta, \get_post_meta($post_id, $meta, \true));
            }
        }
    }
    /**
     * Get `Sync` instance.
     *
     * @codeCoverageIgnore Getter
     */
    public function getSync()
    {
        return $this->sync;
    }
    /**
     * Get compatibility language instance.
     */
    protected function compInstance()
    {
        return $this->sync->compInstance();
    }
}
