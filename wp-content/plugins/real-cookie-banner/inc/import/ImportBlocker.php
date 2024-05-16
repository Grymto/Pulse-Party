<?php

namespace DevOwl\RealCookieBanner\import;

use DevOwl\RealCookieBanner\lite\settings\TcfVendorConfiguration;
use DevOwl\RealCookieBanner\settings\Blocker;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\templates\TemplateConsumers;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Trait to handle the importer for content blocker in the `Import` class.
 * @internal
 */
trait ImportBlocker
{
    /**
     * Import content blocker from JSON.
     *
     * @param array $blockers
     */
    protected function doImportBlocker($blockers)
    {
        $currentBlockers = \DevOwl\RealCookieBanner\import\Export::instance()->appendBlocker()->finish()['blocker'];
        $blockerStatus = $this->getBlockerStatus();
        foreach ($blockers as $index => $blocker) {
            if (!$this->handleCorruptBlocker($blocker, $index)) {
                continue;
            }
            // Collect data
            $metas = $blocker['metas'];
            $post_name = $blocker['post_name'];
            $post_content = $blocker['post_content'];
            $post_status = $blockerStatus === 'keep' ? $blocker['post_status'] : $blockerStatus;
            $post_title = $blocker['post_title'];
            $countAllAssociations = 0;
            $associatedCount = 0;
            $showPreviewImageMediaLibraryMessage = \false;
            if (\is_array($metas)) {
                // Fix meta: rules
                if (isset($metas[Blocker::META_NAME_RULES]) && \is_array($metas[Blocker::META_NAME_RULES])) {
                    $metas[Blocker::META_NAME_RULES] = \join("\n", $metas[Blocker::META_NAME_RULES]);
                }
                // Fix meta: tcfVendors
                if ($this->isPro() && isset($metas[Blocker::META_NAME_TCF_VENDORS]) && \is_array($metas[Blocker::META_NAME_TCF_VENDORS])) {
                    $countAllAssociations += \count($metas[Blocker::META_NAME_TCF_VENDORS]);
                    $metas[Blocker::META_NAME_TCF_VENDORS] = $this->correctAssociationIdsForBlocker($metas[Blocker::META_NAME_TCF_VENDORS], 'mapTcfVendorConfigurations', TcfVendorConfiguration::CPT_NAME);
                    $associatedCount += \count($metas[Blocker::META_NAME_TCF_VENDORS]);
                    $metas[Blocker::META_NAME_TCF_VENDORS] = \join(',', $metas[Blocker::META_NAME_TCF_VENDORS]);
                }
                // Fix meta: tcfPurposes
                if ($this->isPro() && isset($metas[Blocker::META_NAME_TCF_PURPOSES]) && \is_array($metas[Blocker::META_NAME_TCF_PURPOSES])) {
                    $metas[Blocker::META_NAME_TCF_PURPOSES] = \join(',', $metas[Blocker::META_NAME_TCF_PURPOSES]);
                }
                // Fix meta: cookies
                if (isset($metas[Blocker::META_NAME_SERVICES]) && \is_array($metas[Blocker::META_NAME_SERVICES])) {
                    $countAllAssociations += \count($metas[Blocker::META_NAME_SERVICES]);
                    $metas[Blocker::META_NAME_SERVICES] = $this->correctAssociationIdsForBlocker($metas[Blocker::META_NAME_SERVICES], 'mapCookies', Cookie::CPT_NAME);
                    $associatedCount += \count($metas[Blocker::META_NAME_SERVICES]);
                    $metas[Blocker::META_NAME_SERVICES] = \join(',', $metas[Blocker::META_NAME_SERVICES]);
                }
                if (isset($metas[Blocker::META_NAME_VISUAL_MEDIA_THUMBNAIL]) && $metas[Blocker::META_NAME_VISUAL_MEDIA_THUMBNAIL] > 0) {
                    $showPreviewImageMediaLibraryMessage = \true;
                    unset($metas[Blocker::META_NAME_VISUAL_MEDIA_THUMBNAIL]);
                }
            }
            // Find current blocker with same post_name
            $found = \false;
            foreach ($currentBlockers as $currentBlocker) {
                if ($currentBlocker['post_name'] === $post_name) {
                    $found = $currentBlocker;
                    break;
                }
            }
            // Check if this is a PRO template
            if (!$this->isPro() && isset($metas[Blocker::META_NAME_PRESET_ID]) && !empty($metas[Blocker::META_NAME_PRESET_ID])) {
                $templates = TemplateConsumers::getCurrentServiceConsumer()->retrieveBy('identifier', $metas[Blocker::META_NAME_PRESET_ID]);
                if (\count($templates) === 0) {
                    $this->addMessageCreateFailureImportingTemplateButNoLicenseActive($post_title);
                    continue;
                } elseif ($templates[0]->tier !== 'free') {
                    $this->addMessageCreateFailureImportingProInFreeVersion($post_title);
                    continue;
                }
            }
            if ($this->isBlockerSkipExisting() && $found) {
                $this->addMessageSkipExistingBlocker($post_name);
                continue;
            }
            // Always create the entry
            $create = \wp_insert_post(['post_type' => Blocker::CPT_NAME, 'post_content' => $post_content, 'post_title' => $post_title, 'post_status' => $post_status, 'meta_input' => $metas], \true);
            if (\is_wp_error($create)) {
                $this->addMessageCreateFailure($post_name, \__('Content Blocker', RCB_TD), $create);
                continue;
            }
            $this->probablyAddMessageDuplicateBlocker($found, $post_name, $found['ID'], $create);
            $this->probablyAddMessageBlockerAssociation($countAllAssociations, $associatedCount, $post_title, $create);
            if ($showPreviewImageMediaLibraryMessage) {
                $this->addMessageBlockerVisualMediaThumbnail($post_title, $create);
            }
        }
    }
    /**
     * Fetch the correct cookie / TCF vendor ids for the meta.
     *
     * @param string[] $post_names
     * @param string $mapName Can be `mapCookies` or `mapTcfVendorConfigurations`
     * @param string $association_post_type
     */
    protected function correctAssociationIdsForBlocker($post_names, $mapName, $association_post_type)
    {
        global $wpdb;
        $result = [];
        foreach ($post_names as $post_name) {
            // Check if it is an imported post
            if (isset($this->{$mapName}[$post_name])) {
                $result[] = $this->{$mapName}[$post_name];
            } else {
                $post = $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_type = %s", $post_name, $association_post_type));
                if ($post) {
                    $result[] = \get_post($post)->ID;
                }
            }
        }
        return \array_map('intval', $result);
    }
    /**
     * Check missing meta of passed blocker.
     *
     * @param array $blocker
     * @param int $index
     */
    protected function handleCorruptBlocker($blocker, $index)
    {
        if (!isset($blocker['metas'], $blocker['post_name'], $blocker['post_content'], $blocker['post_status'], $blocker['post_title'])) {
            $this->addMessageMissingProperties($index, \__('Content Blocker', RCB_TD), 'ID, metas, post_content, post_name, post_status, post_title');
            return \false;
        }
        return \true;
    }
}
