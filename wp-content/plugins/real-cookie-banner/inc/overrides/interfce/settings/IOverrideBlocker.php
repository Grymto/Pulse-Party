<?php

namespace DevOwl\RealCookieBanner\overrides\interfce\settings;

use WP_Post;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/** @internal */
interface IOverrideBlocker
{
    /**
     * Cast data for `getOrdered` metadata.
     *
     * @param WP_Post $post
     * @param array $meta
     */
    public function overrideGetOrderedCastMeta($post, &$meta);
}
