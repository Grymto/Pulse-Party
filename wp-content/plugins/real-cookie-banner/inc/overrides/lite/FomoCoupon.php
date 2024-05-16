<?php

namespace DevOwl\RealCookieBanner\lite;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Service;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Fetch a FOMO coupon from our Real Commerce backend for free users with valid license.
 * @internal
 */
class FomoCoupon
{
    /**
     * The option value can be one of the following:
     *
     * - `-1`: You already have an in-app FOMO coupon received -> never send a request to the backend again
     * - `number > 0`: Timestamp, when we retry the backend as it was not available
     * - `array`: Result of the Fomo Coupon
     */
    const OPTION_NAME_FOMO_COUPON = RCB_OPT_PREFIX . '-fomo-coupon';
    const ENDPOINT_FOMO_COUPON = '1.0.0/fomo/in-app-coupon';
    const RETRY_ENDPOINT_ON_ERROR_IN_SECONDS = 6 * 60 * 60;
    /**
     * Fetch the FOMO coupon from the Real Commerce database only once and cache it. If there is no free
     * license activated, never fetch a coupon from the external server (GDPR).
     *
     * @return array|false
     */
    protected function fetchFomoCoupon()
    {
        $license = Core::getInstance()->getRpmInitiator()->getPluginUpdater()->getCurrentBlogLicense();
        $licenseActivation = $license->getActivation();
        $code = $licenseActivation->getCode();
        $uuid = $license->getUuid();
        $isLicensed = !empty($code);
        // Already cached?
        $fomoCoupon = \get_option(self::OPTION_NAME_FOMO_COUPON);
        if (\is_numeric($fomoCoupon)) {
            $fomoCoupon = \intval($fomoCoupon);
        }
        if (!\is_array($fomoCoupon) && $isLicensed && $fomoCoupon !== -1) {
            // Check if the FOMO coupon failed recently through another error (e.g. server was down) and retry in x hours
            if (\is_int($fomoCoupon)) {
                if (\time() < $fomoCoupon) {
                    return \false;
                } else {
                    // Reset value, so we can trigger a retry again if server is still not available
                    $fomoCoupon = \false;
                }
            }
            // Do the request to the RCB backend and try to receive a FOMO coupon
            $url = \add_query_arg(['licenseKey' => $code, 'clientUuid' => $uuid], $this->getApiUrl() . self::ENDPOINT_FOMO_COUPON);
            $result = \wp_remote_get($url);
            $resultCode = \wp_remote_retrieve_response_code($result);
            $resultBodyString = \wp_remote_retrieve_body($result);
            if (!empty($resultBodyString)) {
                $resultBody = \json_decode($resultBodyString, ARRAY_A);
                if ($resultCode === 200 && isset($resultBody['inAppFomoCoupon'])) {
                    // Received a valid FOMO coupon
                    $resultBody = $resultBody['inAppFomoCoupon'];
                    $fomoCoupon = $resultBody;
                    \update_option(self::OPTION_NAME_FOMO_COUPON, $fomoCoupon, \false);
                } elseif (\strpos($resultBodyString, 'InAppFomoCouponAlreadyIssued') !== \false) {
                    // FOMO coupon already issued for this client and license key
                    $fomoCoupon = -1;
                    \update_option(self::OPTION_NAME_FOMO_COUPON, $fomoCoupon, \false);
                }
            }
            // If e.g. the server was down, try again in x hours
            if ($fomoCoupon === \false) {
                $fomoCoupon = \time() + self::RETRY_ENDPOINT_ON_ERROR_IN_SECONDS;
                \update_option(self::OPTION_NAME_FOMO_COUPON, $fomoCoupon, \false);
            }
        }
        return \is_array($fomoCoupon) && \strtotime($fomoCoupon['validUntil']) > \time() ? $fomoCoupon : \false;
    }
    /**
     * Output the FOMO coupon to the current revision REST endpoint, so the UI can work with it.
     * If the FOMO coupon is not yet available, fetch it.
     *
     * @param array $arr
     */
    public function revisionCurrent($arr)
    {
        $arr['fomo_coupon'] = $this->fetchFomoCoupon();
        return $arr;
    }
    /**
     * Get the URL to the Real Commerce API.
     */
    protected function getApiUrl()
    {
        return Service::getExternalContainerUrl('commerce');
    }
}
