<?php

namespace DevOwl\RealCookieBanner\view;

use DevOwl\RealCookieBanner\Activator;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\DemoEnvironment;
use DevOwl\RealCookieBanner\view\checklist\AbstractChecklistItem;
use DevOwl\RealCookieBanner\view\checklist\ActivateBanner;
use DevOwl\RealCookieBanner\view\checklist\AddBlocker;
use DevOwl\RealCookieBanner\view\checklist\AddCookie;
use DevOwl\RealCookieBanner\view\checklist\CustomizeBanner;
use DevOwl\RealCookieBanner\view\checklist\GetPro;
use DevOwl\RealCookieBanner\view\checklist\Install;
use DevOwl\RealCookieBanner\view\checklist\License;
use DevOwl\RealCookieBanner\view\checklist\OperatorContact;
use DevOwl\RealCookieBanner\view\checklist\PrivacyPolicy;
use DevOwl\RealCookieBanner\view\checklist\PrivacyPolicyMentionUsage;
use DevOwl\RealCookieBanner\view\checklist\SaveSettings;
use DevOwl\RealCookieBanner\view\checklist\Scanner;
use DevOwl\RealCookieBanner\view\checklist\Shortcode;
use DevOwl\RealCookieBanner\view\checklist\ViewStats;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Checklist handler.
 * @internal
 */
class Checklist
{
    use UtilsProvider;
    const ITEMS_ORDERED = [Install::IDENTIFIER => Install::class, License::IDENTIFIER => License::class, GetPro::IDENTIFIER => GetPro::class, SaveSettings::IDENTIFIER => SaveSettings::class, PrivacyPolicy::IDENTIFIER => PrivacyPolicy::class, OperatorContact::IDENTIFIER => OperatorContact::class, PrivacyPolicyMentionUsage::IDENTIFIER => PrivacyPolicyMentionUsage::class, Scanner::IDENTIFIER => Scanner::class, AddCookie::IDENTIFIER => AddCookie::class, CustomizeBanner::IDENTIFIER => CustomizeBanner::class, AddBlocker::IDENTIFIER => AddBlocker::class, ActivateBanner::IDENTIFIER => ActivateBanner::class, Shortcode::IDENTIFIER => Shortcode::class, ViewStats::IDENTIFIER => ViewStats::class];
    /**
     * Singleton instance.
     *
     * @var Checklist
     */
    private static $me = null;
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Get current checklist result.
     */
    public function result()
    {
        $result = [];
        foreach (self::ITEMS_ORDERED as $id => $clazz) {
            /**
             * Instance.
             *
             * @var AbstractChecklistItem
             */
            $instance = new $clazz();
            if (!$instance->isVisible()) {
                continue;
            }
            $result[$id] = ['title' => $instance->getTitle(), 'description' => $instance->getDescription(), 'checked' => $instance->isChecked() || $this->isAllChecked(), 'link' => $instance->getLink(), 'linkText' => $instance->getLinkText(), 'linkTarget' => $instance->getLinkTarget(), 'needsPro' => $instance->needsPro()];
        }
        return ['dismissed' => $this->isAllChecked(), 'items' => $result];
    }
    /**
     * Toggle a checklist item checked state. If you pass 'all' as ID, all
     * checkbox items will be marked as checked.
     *
     * @param string $id
     * @param boolean $state
     * @return boolean
     */
    public function toggle($id, $state)
    {
        if ($id === 'all') {
            return Core::getInstance()->getNotices()->setChecklistItem('all', \true);
        }
        if (\array_key_exists($id, self::ITEMS_ORDERED)) {
            $clazz = self::ITEMS_ORDERED[$id];
            return (new $clazz())->toggle($state);
        }
        return \false;
    }
    /**
     * Check if the checklist is completed.
     */
    public function isCompleted()
    {
        $result = $this->result();
        $isPro = $this->isPro();
        if ($result['dismissed']) {
            return \true;
        }
        $items = $result['items'];
        $completed = \array_filter($items, function ($item) {
            return $item['checked'];
        });
        $checkable = \array_filter($items, function ($item) use($isPro) {
            return !$item['needsPro'] || $isPro && $item['needsPro'];
        });
        return \count($completed) >= \count($checkable);
    }
    /**
     * Check if the checklist is not completed yet and is overdue e.g. 2 weeks.
     *
     * @param string $time E.g. `+2 weeks`
     */
    public function isOverdue($time)
    {
        $completed = $this->isCompleted();
        if ($completed || DemoEnvironment::getInstance()->isDemoEnv()) {
            return \false;
        }
        $installed = \strtotime(\get_option(Activator::OPTION_NAME_INSTALLATION_DATE));
        return \time() > $installed + \strtotime($time, 0);
    }
    /**
     * Check if all items are checked through "I have already done all the steps".
     *
     * @return boolean
     */
    public function isAllChecked()
    {
        return Core::getInstance()->getNotices()->isChecklistItem('all');
    }
    /**
     * Check if any of items is checked
     *
     * @param string $identifier One of the ITEMS_ORDERED
     * @return boolean
     */
    public function isChecked($identifier)
    {
        $result = $this->result()['items'][$identifier] ?? null;
        return $result['checked'] ?? \false;
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\view\Checklist() : self::$me;
    }
}
