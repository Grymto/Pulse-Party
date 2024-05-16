<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings;

/**
 * Abstract implementation of the settings for a multisite network (Consent Forwarding).
 * @internal
 */
abstract class AbstractMultisite extends BaseSettings
{
    const ENDPOINT_FILTER_ALL = 'all';
    const ENDPOINT_FILTER_NOT_CURRENT = 'notCurrent';
    const ENDPOINT_FILTER_ONLY_CURRENT = 'onlyCurrent';
    const ALL_ENDPOINT_FILTERS = [self::ENDPOINT_FILTER_ALL, self::ENDPOINT_FILTER_NOT_CURRENT, self::ENDPOINT_FILTER_ONLY_CURRENT];
    /**
     * Check if consent should be forwarded.
     *
     * @return boolean
     */
    public abstract function isConsentForwarding();
    /**
     * Get forward to URLs within the network.
     *
     * @return string[]
     */
    public abstract function getForwardTo();
    /**
     * Get forward to external URLs.
     *
     * @return string[]
     */
    public abstract function getCrossDomains();
    /**
     * Get current host for the current environment where consent is processed.
     *
     * @return string
     */
    public abstract function getEnvironmentHost();
    /**
     * Get the available endpoints which are part of this environment and active for the consent forwarding. Examples:
     *
     * - WordPress Multisite: Other subsites within the multisite network
     * - WordPress Multilingual: Other URLs to other languages of the current website
     *
     * @return string[]
     */
    public abstract function getConfiguredEndpoints();
    /**
     * Get all available and predefined endpoints within this environment. E. g. if your are running within a mulitisite,
     * you will get all available WP REST API endpoints for each site within this multisite. That means, you need
     * to add your endpoint as follows: `$endpoints["https://example.com/wp-json/real-cookie-banner/v1/consent/forward"] = 'My example'.`
     *
     * @param string $filter Can be `all`, `notCurrent` and `onlyCurrent`
     * @return array
     */
    public abstract function getAvailableEndpoints($filter = self::ENDPOINT_FILTER_ALL);
    /**
     * Transform a given consent decision in string[] (`uniqueName`) to a map of groups with cookie ids.
     * It automatically accepts all essential services.
     *
     * @param string[] $uniqueNames
     */
    public function mapUniqueNamesToDecision($uniqueNames)
    {
        $decision = [];
        foreach ($this->getSettings()->getGeneral()->getServiceGroups() as $group) {
            foreach ($group->getItems() as $service) {
                if ($group->isEssential() || \in_array($service->getUniqueName(), $uniqueNames, \true)) {
                    $groupId = $group->getId();
                    if (!isset($decision[$groupId])) {
                        $decision[$groupId] = [];
                    }
                    $decision[$groupId][] = $service->getId();
                }
            }
        }
        return $decision;
    }
    /**
     * Transform a given consent decision (groups with service IDs) to a map of unique names.
     *
     * @param array $decision
     * @return string[]
     */
    public function mapDecisionToUniqueNames($decision)
    {
        $uniqueNames = [];
        $mapServiceIdToUniqueName = [];
        foreach ($this->getSettings()->getGeneral()->getServiceGroups() as $group) {
            foreach ($group->getItems() as $service) {
                if (!empty($service->getUniqueName())) {
                    $mapServiceIdToUniqueName[$service->getId()] = $service->getUniqueName();
                }
            }
        }
        foreach ($decision as $serviceIds) {
            foreach ($serviceIds as $serviceId) {
                if (isset($mapServiceIdToUniqueName[$serviceId])) {
                    $uniqueNames[] = $mapServiceIdToUniqueName[$serviceId];
                }
            }
        }
        return $uniqueNames;
    }
    /**
     * Check if external hosts need to be contacted and return the external hosts including subdomain.
     * Returns `false` if no external host gets contacted.
     *
     * @return string[]
     */
    public function getExternalHosts()
    {
        if ($this->isConsentForwarding()) {
            $currentHost = $this->getEnvironmentHost();
            $urls = $this->getConfiguredEndpoints();
            $hosts = \array_values(
                // `array_values` again cause we need to force an array instead of object (https://stackoverflow.com/a/25296770/5506547)
                \array_unique(\array_values(\array_filter(\array_map(function ($url) {
                    return \parse_url($url, \PHP_URL_HOST);
                }, $urls), function ($url) use($currentHost) {
                    return $currentHost !== $url;
                })))
            );
            return \count($hosts) > 0 ? $hosts : \false;
        }
        return \false;
    }
}
