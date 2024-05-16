<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\frontend;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\Utils;
use Requests_Cookie;
use Requests_Response_Headers;
use WpOrg\Requests\Cookie;
use WpOrg\Requests\Response\Headers;
/**
 * This class allows you to simulate a call to the REST API which saves consent and check if saving
 * worked as expected. It analyzes the result of the request response when the request failed and
 * returns suggestions, if available.
 * @internal
 */
class SavingConsentViaRestApiEndpointChecker
{
    const ERROR_DIAGNOSTIC_ERROR_CODE = 'errorCode';
    const ERROR_DIAGNOSTIC_RESPONSE_BODY = 'responseBody';
    const ERROR_DIAGNOSTIC_NO_COOKIES = 'noCookies';
    const ERROR_DIAGNOSTIC_COOKIE_PATH = 'cookiePath';
    const ERROR_DIAGNOSTIC_COOKIE_HTTP_ONLY = 'cookieHttpOnly';
    const ERROR_DIAGNOSTIC_REDIRECT = 'redirect';
    const ERROR_DIAGNOSTIC_403_FORBIDDEN_HTACCESS_DENY = '403htaccessDeny';
    /**
     * Start time.
     *
     * @var float
     */
    private $start;
    /**
     * Call this method directly before you do the dummy REST API call.
     */
    public function start()
    {
        $this->start = \microtime(\true);
    }
    /**
     * Call this method directly after you do the dummy REST API call.
     *
     * Additional arguments:
     *
     * ```
     * string       'htaccess'         The content of the .htaccess file for checks when the response receives a 403 Forbidden error
     * string[]     'internalIps'      An string array of all internal IPs, needed for `htaccess`
     * ```
     *
     * @param string $bodyString The response body
     * @param array $headers The response headers
     * @param int $code The response status code
     * @param array $args Additional arguments
     */
    public function teardown($bodyString, $headers, $code, $args = [])
    {
        $errors = [];
        //$time = microtime(true) - $this->start;
        // The response did not work
        if ($code < 200 || $code > 299) {
            $errors[] = [self::ERROR_DIAGNOSTIC_ERROR_CODE, $code];
            if (!empty($bodyString)) {
                $errors[] = [self::ERROR_DIAGNOSTIC_RESPONSE_BODY, $bodyString];
            }
            // Is the loopback request perhaps blocked by `.htaccess`?
            // Some customers told me that they have denied the own IP as some plugins are doing too
            // many loop back requests.
            $htaccess = $args['htaccess'] ?? \false;
            $internalIps = $args['internalIps'] ?? \false;
            if ($code === 403 && \is_string($htaccess) && \is_array($internalIps) && \count($internalIps) > 0) {
                // Split line by line
                $htaccess = \explode("\n", $htaccess);
                foreach ($htaccess as $line) {
                    foreach ($internalIps as $ip) {
                        if (\strpos($line, $ip) !== \false) {
                            $errors[] = [self::ERROR_DIAGNOSTIC_403_FORBIDDEN_HTACCESS_DENY, $line];
                        }
                    }
                }
            }
            return $errors;
        }
        // WordPress backwards-compatibilty 6.1
        if (\class_exists(Headers::class)) {
            $headersInstance = new Headers();
        } else {
            $headersInstance = new Requests_Response_Headers();
        }
        foreach ($headers as $key => $value) {
            $headersInstance[$key] = $value;
        }
        $cookies = self::parseCookiesFromHeaders($headersInstance);
        if (\count($cookies) > 0) {
            // Check for invalid path
            foreach ($cookies as $cookie) {
                $pathIsValid = Utils::startsWith($cookie->attributes['path'] ?? '/', '/');
                if (!$pathIsValid) {
                    $errors[] = [self::ERROR_DIAGNOSTIC_COOKIE_PATH, $cookie->name, $cookie->attributes['path']];
                    break;
                }
            }
            // Check for HttpOnly flag
            foreach ($cookies as $cookie) {
                if ($cookie->attributes['httponly'] ?? \false) {
                    $errors[] = [self::ERROR_DIAGNOSTIC_COOKIE_HTTP_ONLY, $cookie->name];
                    break;
                }
            }
        } else {
            $errors[] = [self::ERROR_DIAGNOSTIC_NO_COOKIES];
        }
        // Check for a `Location` redirect
        $location = $headersInstance->getValues('Location');
        if (!empty($location)) {
            $errors[] = [self::ERROR_DIAGNOSTIC_REDIRECT, $location[0]];
        }
        // For testing purposes
        /*$errors[] = [self::ERROR_DIAGNOSTIC_ERROR_CODE, 500];
          $errors[] = [self::ERROR_DIAGNOSTIC_RESPONSE_BODY, 'This request is blocked'];
          $errors[] = [self::ERROR_DIAGNOSTIC_NO_COOKIES];
          $errors[] = [self::ERROR_DIAGNOSTIC_COOKIE_PATH, 'my_cookie', 'httpss://'];
          $errors[] = [self::ERROR_DIAGNOSTIC_COOKIE_HTTP_ONLY, 'my_cookie'];
          $errors[] = [self::ERROR_DIAGNOSTIC_REDIRECT, 'https://example.com'];*/
        return $errors;
    }
    /**
     * Parse the `Set-cookie` headers to a valid `Cookie` instance, but only the cookies starting with `real_cookie_banner`.
     *
     * @param Headers $headers
     * @see https://github.com/WordPress/Requests/blob/47a1b69b618b136bdbd98af693cc73b850b78808/src/Cookie.php#L487-L495
     * @return Cookie[]
     */
    protected static function parseCookiesFromHeaders($headers)
    {
        $cookieHeaders = Utils::array_flatten($headers->getValues('Set-Cookie'));
        if (empty($cookieHeaders)) {
            return [];
        }
        $cookies = [];
        foreach ($cookieHeaders as $header) {
            // WordPress backwards-compatibilty 6.1
            if (\class_exists(Cookie::class)) {
                $parsed = Cookie::parse($header, '', null);
            } else {
                $parsed = Requests_Cookie::parse($header, '', null);
            }
            if (Utils::startsWith($parsed->name, 'real_cookie_banner')) {
                $cookies[] = $parsed;
            }
        }
        return $cookies;
    }
}
