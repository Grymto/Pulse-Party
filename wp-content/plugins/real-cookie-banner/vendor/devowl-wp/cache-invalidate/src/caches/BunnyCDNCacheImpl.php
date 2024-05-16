<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use BunnyCDN;
/**
 * BunnyCDN.
 *
 * @see https://wordpress.org/plugins/bunnycdn/
 * @codeCoverageIgnore
 * @internal
 */
class BunnyCDNCacheImpl extends AbstractCache
{
    const IDENTIFIER = 'bunny-cdn';
    // Documented in AbstractCache
    public function isActive()
    {
        if (!\class_exists(BunnyCDN::class) || !\method_exists(BunnyCDN::class, 'getOptions')) {
            return \false;
        }
        $options = BunnyCDN::getOptions();
        return \is_array($options) && !empty($options['api_key']) && !empty($options['cdn_domain_name']);
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        $options = BunnyCDN::getOptions();
        $apiKey = $options['api_key'];
        $cdnDomainName = $options['cdn_domain_name'];
        $response = \wp_remote_post(\sprintf('https://bunnycdn.com/api/pullzone/purgeCacheByHostname?hostname=%s', \urlencode($cdnDomainName)), ['timeout' => 5, 'headers' => ['AccessKey' => $apiKey]]);
        if (\is_wp_error($response)) {
            return ['error' => ['code' => $response->get_error_code(), 'get_error_message' => $response->get_error_message()]];
        } else {
            return ['httpCode' => \wp_remote_retrieve_response_code($response)];
        }
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'BunnyCDN';
    }
}
