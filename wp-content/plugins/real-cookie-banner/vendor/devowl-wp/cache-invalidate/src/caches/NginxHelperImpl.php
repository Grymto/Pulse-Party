<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
/**
 * Nginx Helper.
 *
 * @see https://wordpress.org/plugins/nginx-helper/
 * @see https://github.com/rtCamp/nginx-helper/blob/58e6f0a37673e395ea7e7ab10f31f417e8e468d3/class-nginx-helper-wp-cli-command.php#L34-L41
 * @codeCoverageIgnore
 * @internal
 */
class NginxHelperImpl extends AbstractCache
{
    const IDENTIFIER = 'nginx-helper';
    // https://github.com/rtCamp/nginx-helper/blob/58e6f0a37673e395ea7e7ab10f31f417e8e468d3/includes/class-nginx-helper.php#L176-L190
    const AVAILABLE_PURGE_CLASSES = ['FastCGI_Purger', 'PhpRedis_Purger', 'Predis_Purger'];
    // Documented in AbstractCache
    public function isActive()
    {
        global $nginx_purger;
        return isset($nginx_purger) && \in_array(\get_class($nginx_purger), self::AVAILABLE_PURGE_CLASSES, \true);
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        global $nginx_purger;
        return $nginx_purger->purge_all();
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'Nginx Helper';
    }
}
