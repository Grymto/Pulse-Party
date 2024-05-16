<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\queue;

use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\UtilsProvider;
use WP_Error;
// use WP_Error;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Simple executor which does nothing.
 * @internal
 */
class NoopExecutor
{
    use UtilsProvider;
    /**
     * Do nothing, simple sleep a second.
     *
     * @param Job $job
     */
    public static function execute($job)
    {
        // Just some tests
        /*if ($job->id === 1) {
              $job->updateProcess(true);
          }
          if ($job->id === 2) {
              return new WP_Error('noop_exception', 'Something went wrong', ['timestamp' => time()]);
          }*/
        if ($job->id === 2) {
            // && $job->process === 0) {
            return new WP_Error('noop_exception', 'Something went wrong', ['timestamp' => \time()]);
        } else {
            \sleep(1);
            $job->updateProcess($job->process + 1);
        }
    }
}
