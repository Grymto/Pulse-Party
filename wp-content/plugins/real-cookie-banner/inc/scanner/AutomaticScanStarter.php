<?php

namespace DevOwl\RealCookieBanner\scanner;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\queue\Job;
use stdClass;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Automatically start the scan process when the user has first interaction with
 * the plugin.
 * @internal
 */
class AutomaticScanStarter
{
    use UtilsProvider;
    const OPTION_NAME = RCB_OPT_PREFIX . '-automatic-scan-starter';
    const REAL_QUEUE_TYPE = 'rcb-automatic-scan-starter';
    const STATUS_PENDING = 'pending';
    // Nothing done yet
    const STATUS_INITIALIZED = 'initialized';
    // Job to read sitemap got created
    const STATUS_STARTED = 'started';
    /**
     * C'tor.
     *
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Check if the job to start the scanner can be added.
     */
    public function probablyAddClientJob()
    {
        $status = $this->getStatus();
        // The scan process has already been initialized (do nothing)
        if ($status !== self::STATUS_PENDING) {
            return;
        }
        $licenseActivation = Core::getInstance()->getRpmInitiator()->getPluginUpdater()->getCurrentBlogLicense()->getActivation();
        $isLicensed = !empty($licenseActivation->getCode());
        // Check if the plugin is licensed (the user has accepted the privacy policy)
        if ($isLicensed) {
            $this->addJobToQueue();
            \update_option(self::OPTION_NAME, self::STATUS_INITIALIZED);
        }
    }
    /**
     * Add the job to start the scanner to the `real-queue`.
     */
    public function addJobToQueue()
    {
        // Add client job to start the scan process
        $queue = Core::getInstance()->getRealQueue();
        $persist = $queue->getPersist();
        $persist->startTransaction();
        $job = new Job($queue);
        $job->worker = Job::WORKER_CLIENT;
        $job->type = self::REAL_QUEUE_TYPE;
        $job->data = new stdClass();
        $job->retries = 1;
        $persist->addJob($job);
        $persist->commit();
    }
    /**
     * Get the status of the automatic scan process.
     *
     * @return string
     */
    public function getStatus()
    {
        $status = \get_option(self::OPTION_NAME);
        if ($status === \false) {
            \update_option(self::OPTION_NAME, self::STATUS_PENDING);
            return self::STATUS_PENDING;
        }
        return $status;
    }
    /**
     * New instance.
     *
     * @codeCoverageIgnore
     */
    public static function instance()
    {
        return new \DevOwl\RealCookieBanner\scanner\AutomaticScanStarter();
    }
}
