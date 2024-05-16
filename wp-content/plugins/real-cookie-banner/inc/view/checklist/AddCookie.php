<?php

namespace DevOwl\RealCookieBanner\view\checklist;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\view\Checklist;
use WP_Post;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Add first cookie to website.
 * @internal
 */
class AddCookie extends \DevOwl\RealCookieBanner\view\checklist\AbstractChecklistItem
{
    const IDENTIFIER = 'add-cookie';
    // Documented in AbstractChecklistItem
    public function isChecked()
    {
        return $this->getFromOption(self::IDENTIFIER);
    }
    // Documented in AbstractChecklistItem
    public function toggle($state)
    {
        return $this->persistStateToOption(self::IDENTIFIER, $state);
    }
    // Documented in AbstractChecklistItem
    public function getTitle()
    {
        return \__('Add all services (cookies) from your website', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getDescription()
    {
        return \__('You can manually add all services from your website. Use the cookie templates of known services to save time!', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getLink()
    {
        return '#/cookies';
    }
    // Documented in AbstractChecklistItem
    public function getLinkText()
    {
        return \__('Add service', RCB_TD);
    }
    /**
     * A cookie was saved, check if non-RCB cookie and newly created.
     *
     * @param int $post_ID
     * @param WP_Post $post
     * @param boolean $update
     */
    public static function save_post($post_ID, $post, $update)
    {
        if (!$update && \strpos($post->post_name, 'real-cookie-banner', 0) !== 0) {
            Checklist::getInstance()->toggle(self::IDENTIFIER, \true);
        }
        // Keep "Already created" in cookie templates intact
        if (!$update) {
            \wp_rcb_invalidate_templates_cache(\true);
        }
        Core::getInstance()->getNotices()->recalculatePostDependingNotices();
    }
}
