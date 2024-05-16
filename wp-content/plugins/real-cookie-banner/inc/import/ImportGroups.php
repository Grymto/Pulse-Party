<?php

namespace DevOwl\RealCookieBanner\import;

use DevOwl\RealCookieBanner\settings\CookieGroup;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Trait to handle the importer for groups in the `Import` class.
 * @internal
 */
trait ImportGroups
{
    /**
     * Import cookie groups from JSON.
     *
     * @param array $groups
     */
    protected function doImportCookieGroups($groups)
    {
        $currentGroups = \DevOwl\RealCookieBanner\import\Export::instance()->appendCookieGroups()->finish()['cookieGroups'];
        $order = \count($currentGroups);
        foreach ($groups as $index => $group) {
            if (!$this->handleCorruptGroup($group, $index)) {
                continue;
            }
            $slug = $group['slug'];
            $name = $group['name'];
            $description = $group['description'];
            // Find current group with same slug
            $found = \false;
            foreach ($currentGroups as $currentGroup) {
                if ($currentGroup['slug'] === $slug) {
                    $found = $currentGroup;
                    break;
                }
            }
            // We found existing group, just update...
            if ($found !== \false) {
                $this->handleGroupUpdate($slug, $found['term_id'], $name, $description);
            } elseif ($this->handleGroupCreate($slug, $name, $description, $order)) {
                ++$order;
            }
        }
    }
    /**
     * Handle to update an existing group instead of creating a new one.
     *
     * @param string $slug
     * @param int $term_id
     * @param string $name
     * @param string $description
     */
    protected function handleGroupUpdate($slug, $term_id, $name, $description)
    {
        $update = \wp_update_term($term_id, CookieGroup::TAXONOMY_NAME, ['name' => $name, 'description' => $description]);
        if (\is_wp_error($update)) {
            $this->addMessageCookieGroupUpdateFailure($name, $update);
        } else {
            $this->addMessageCookieGroupUpdatedInfo($name);
        }
    }
    /**
     * Handle to create a new group.
     *
     * @param string $slug
     * @param string $name
     * @param string $description
     * @param int $order
     */
    protected function handleGroupCreate($slug, $name, $description, $order)
    {
        // Try to create
        $create = \wp_insert_term($name, CookieGroup::TAXONOMY_NAME, ['name' => $name, 'description' => $description, 'slug' => $slug]);
        if (\is_wp_error($create)) {
            $this->addMessageCreateFailure($name, \__('Service Group', RCB_TD), $create);
            return \false;
        } else {
            \update_term_meta($create['term_id'], CookieGroup::META_NAME_ORDER, $order);
            return \true;
        }
    }
    /**
     * Check missing meta of passed group.
     *
     * @param array $group
     * @param int $index
     */
    protected function handleCorruptGroup($group, $index)
    {
        if (!isset($group['slug'], $group['name'], $group['description'])) {
            $this->addMessageMissingProperties($index, \__('Service Group', RCB_TD), 'slug, name, description');
            return \false;
        }
        return \true;
    }
}
