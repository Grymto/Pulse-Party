<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
/**
 * Enhance.com NGINX FastCGI cache.
 *
 * @see https://apidocs.enhance.com/#/websites/clearDomainNginxFastCgi
 * @codeCoverageIgnore
 * @internal
 */
class EnhanceComNginxFastCgiImpl extends AbstractCache
{
    const IDENTIFIER = 'enhance-com-nginx-fastcgi';
    // Documented in AbstractCache
    public function isActive()
    {
        return \defined('ENHANCE_COM_NGINX_FAST_CGI_INVALIDATE_API_HOST') && \defined('ENHANCE_COM_NGINX_FAST_CGI_INVALIDATE_ORG_ID') && \defined('ENHANCE_COM_NGINX_FAST_CGI_INVALIDATE_ACCESS_TOKEN');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        $apiEndpoint = \untrailingslashit(\constant('ENHANCE_COM_NGINX_FAST_CGI_INVALIDATE_API_HOST'));
        $orgId = \constant('ENHANCE_COM_NGINX_FAST_CGI_INVALIDATE_ORG_ID');
        $accessToken = \constant('ENHANCE_COM_NGINX_FAST_CGI_INVALIDATE_ACCESS_TOKEN');
        $headers = ['Authorization' => \sprintf('Bearer %s', $accessToken)];
        // Fetch all domains associated to this organization
        $domainsUrl = \sprintf('%s/api/v2/orgs/%s/domains?limit=1000', $apiEndpoint, $orgId);
        $domains = \wp_remote_get($domainsUrl, ['headers' => $headers]);
        if (\wp_remote_retrieve_response_code($domains) !== 200) {
            return \false;
        }
        $domains = \json_decode(\wp_remote_retrieve_body($domains), ARRAY_A);
        $domains = $domains['items'] ?? [];
        // Find domain mapped to our WordPress installation
        $host = $_SERVER['HTTP_HOST'];
        foreach ($domains as $item) {
            if ($item['domain'] === $host) {
                $clearUrl = \sprintf('%s/api/v2/domains/%s/nginx_fastcgi', $apiEndpoint, $item['id']);
                return \wp_remote_retrieve_response_code(\wp_remote_request($clearUrl, ['method' => 'DELETE', 'headers' => $headers]));
            }
        }
        return \false;
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'Enhance.com NGINX FastCGI';
    }
}
