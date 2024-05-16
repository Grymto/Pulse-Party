<?php

// namespace DevOwl\RealCookieBanner\Vendor; // excluded from scope due to API exposing

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\scanner\Query;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\CookieGroup;
use DevOwl\RealCookieBanner\templates\TemplateConsumers;
use DevOwl\RealCookieBanner\view\Scanner;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
if (!\function_exists('wp_rcb_invalidate_templates_cache')) {
    /**
     * Invalidate all template caches so they gets recalculated (this does not necessarily download from cloud API!).
     *
     * @param boolean $skipServiceCloudConsumerInvalidation
     * @since 3.6.4
     * @internal
     */
    function wp_rcb_invalidate_templates_cache($skipServiceCloudConsumerInvalidation = \false)
    {
        $fn = function () use($skipServiceCloudConsumerInvalidation) {
            \delete_transient(Scanner::TRANSIENT_SERVICES_FOR_NOTICE);
            \delete_transient(Query::TRANSIENT_SCANNED_EXTERNAL_URLS);
            Core::getInstance()->getNotices()->recalculatePostDependingNotices();
            // Force that middlewares gets recalculated
            if (!$skipServiceCloudConsumerInvalidation) {
                TemplateConsumers::getInstance()->forceRecalculation();
            }
        };
        // Retrigger templates regeneration (do this in init so all multilingual plugins are ready)
        if (\did_action('init')) {
            $fn();
        } else {
            \add_action('init', $fn, 8);
        }
    }
}
if (!\function_exists('wp_rcb_invalidate_presets_cache')) {
    /**
     * Invalidate all template caches so they gets recalculated.
     *
     * @since 2.14.1
     * @deprecated Use `wp_rcb_invalidate_templates_cache` instead
     * @internal
     */
    function wp_rcb_invalidate_presets_cache()
    {
        \wp_rcb_invalidate_templates_cache();
    }
}
if (!\function_exists('wp_rcb_service_groups')) {
    /**
     * Get a list of all existing service groups.
     *
     * @since 2.3.0
     * @internal
     */
    function wp_rcb_service_groups()
    {
        return CookieGroup::getInstance()->getOrdered();
    }
}
if (!\function_exists('wp_rcb_services_by_group')) {
    /**
     * Get a list of all existing services within a group.
     *
     * Example: Get all available service groups and services
     *
     * <code>
     * <?php
     * if (function_exists('wp_rcb_service_groups')) {
     *     foreach (wp_rcb_service_groups() as $group) {
     *         foreach (wp_rcb_services_by_group($group->term_id) as $service) {
     *             printf(
     *                 'Group: %s, Service: %s Service-ID: %d<br />',
     *                 $group->name,
     *                 $service->post_title,
     *                 $service->ID
     *             );
     *         }
     *     }
     * }
     * </code>
     *
     * @param int $group_id The `term_id` of the group
     * @param string|string[] $post_status Pass `null` to read all existing
     * @since 2.3.0
     * @internal
     */
    function wp_rcb_services_by_group($group_id, $post_status = null)
    {
        $query = ['post_type' => Cookie::CPT_NAME, 'orderby' => ['menu_order' => 'ASC', 'ID' => 'DESC'], 'numberposts' => -1, 'nopaging' => \true];
        if ($post_status !== null) {
            $query['post_status'] = $post_status;
        }
        return Cookie::getInstance()->getOrdered($group_id, \false, \get_posts(Core::getInstance()->queryArguments($query, 'wp_rcb_services_by_group')));
    }
}
