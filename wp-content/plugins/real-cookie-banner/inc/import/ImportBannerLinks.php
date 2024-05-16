<?php

namespace DevOwl\RealCookieBanner\import;

use DevOwl\RealCookieBanner\settings\BannerLink;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Trait to handle the importer for banner links in the `Import` class.
 * @internal
 */
trait ImportBannerLinks
{
    /**
     * Import banner links from JSON.
     *
     * @param array $bannerLinks
     */
    protected function doImportBannerLinks($bannerLinks)
    {
        $currentBannerLinks = \DevOwl\RealCookieBanner\import\Export::instance()->appendBannerLinks()->finish()['bannerLinks'];
        foreach ($bannerLinks as $index => $bannerLink) {
            if (!$this->handleCorruptBannerLink($bannerLink, $index)) {
                continue;
            }
            // Collect data
            $metas = $bannerLink['metas'];
            $post_name = $bannerLink['post_name'];
            $post_content = '';
            $post_status = 'publish';
            $post_title = $bannerLink['post_title'];
            $showPageIdMessage = \false;
            if (\is_array($metas)) {
                if (isset($metas[BannerLink::META_NAME_PAGE_ID]) && $metas[BannerLink::META_NAME_PAGE_ID] > 0) {
                    $showPageIdMessage = \true;
                    $metas[BannerLink::META_NAME_PAGE_ID] = 0;
                }
            }
            // Find current blocker with same post_name or same type (legal notice and privacy policy may only exist once)
            $found = \false;
            foreach ($currentBannerLinks as $currentBannerLink) {
                if ($currentBannerLink['post_name'] === $post_name || $metas[BannerLink::META_NAME_PAGE_TYPE] !== 'other' && $currentBannerLink === $metas[BannerLink::META_NAME_PAGE_TYPE]) {
                    $found = $currentBannerLink;
                    break;
                }
            }
            if ($found) {
                $this->addMessageSkipExistingBannerLink($post_name);
                continue;
            }
            // Always create the entry
            $create = \wp_insert_post(['post_type' => BannerLink::CPT_NAME, 'post_content' => $post_content, 'post_title' => $post_title, 'post_status' => $post_status, 'meta_input' => $metas, 'menu_order' => 99], \true);
            if (\is_wp_error($create)) {
                $this->addMessageCreateFailure($post_name, \__('Footer link', RCB_TD), $create);
                continue;
            }
            if ($showPageIdMessage) {
                $this->addMessageBannerLinkPageId($post_title);
            }
        }
    }
    /**
     * Check missing meta of passed banner link.
     *
     * @param array $bannerLink
     * @param int $index
     */
    protected function handleCorruptBannerLink($bannerLink, $index)
    {
        if (!isset($bannerLink['metas'], $bannerLink['post_name'], $bannerLink['post_title'])) {
            $this->addMessageMissingProperties($index, \__('Footer links', RCB_TD), 'ID, metas, post_name, post_status, post_title');
            return \false;
        }
        return \true;
    }
}
