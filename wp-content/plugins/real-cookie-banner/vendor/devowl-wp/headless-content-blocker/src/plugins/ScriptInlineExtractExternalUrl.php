<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\AbstractMatcher;
/**
 * Detect semantically a script which could be loaded by an inline script.
 * If it finds an URL, it is put to the `BlockedResult` as `Plugin.ScriptInlineExtractExternalUrl`.
 *
 * Example:
 *
 * ```
 * <script>(function(w, d, s, f, js, fjs) {
 *     (js = d.createElement(s)), (fjs = d.getElementsByTagName(s)[0]);
 *     w._tbSettings = {
 *       tblocid: "DSPSxJyFMG5kkLGHq", width: "305px", color: "39a7df" };
 *     js.src = f; js.async = 1;
 *     fjs.parentNode.insertBefore(js, fjs);
 * })(window, document, "script", "https://static.teburio.de/widget.js");
 * </script>
 * ```
 * @internal
 */
class ScriptInlineExtractExternalUrl extends AbstractPlugin
{
    const BLOCKED_RESULT_DATA_KEY = 'Plugin.ScriptInlineExtractExternalUrl';
    /**
     * See `AbstractPlugin`.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function checkResult($result, $matcher, $match)
    {
        $markup = $result->getMarkup();
        if ($match->getTag() === 'script' && $markup !== null) {
            $url = self::extractExternalUrlFromInline($markup->getContent());
            if (!empty($url)) {
                $result->setData(self::BLOCKED_RESULT_DATA_KEY, $url);
            }
        }
        return $result;
    }
    /**
     * Extract the URL from a given script-markup.
     *
     * @param string $markup
     */
    public static function extractExternalUrlFromInline($markup)
    {
        if (empty($markup) || \strpos($markup, Constants::HTML_ATTRIBUTE_INLINE) !== \false) {
            return \false;
        }
        /*error_log('--- ' . $markup);
          error_log('-----' . json_encode([
              stripos($markup, '.createElement(') !== false,
              preg_match('/["\']script["\']/m', $markup),
              preg_match('/\.src\s*=*\s*|setAttribute\(\s*[\'"]src/m', $markup),
              preg_match('/\.(?:insertBefore|appendChild)\s*\(/m', $markup),
              preg_match_all('/["\']((?:http[s]?|[:]?\/\/)[^"\']+)["\']/m', $markup, $urlMatches, PREG_SET_ORDER, 0),
          ]));*/
        if (\stripos($markup, '.createElement(') !== \false && \preg_match('/["\']script["\']/m', $markup) && \preg_match('/\\.src\\s*=*\\s*|setAttribute\\(\\s*[\'"]src/m', $markup) && \preg_match('/\\.(?:insertBefore|appendChild)\\s*\\(/m', $markup) && \preg_match_all('/["\']((?:http[s]?|[:]?\\/\\/)[^"\']+)["\']/m', $markup, $urlMatches, \PREG_SET_ORDER, 0)) {
            // Find first valid external URL (e.g. URLs without Scheme)
            foreach ($urlMatches as $match) {
                // Fix e.g. `:(document.location.protocol == "https:" ? "https" : "http") + "://tm.tradetracker.net/tag?t="`
                // Will result in `$match[1] = "://tm.tradetracker.net/tag?t="`
                $url = \trim($match[1], ':');
                if (\filter_var(Utils::setUrlSchema($url, 'http'), \FILTER_VALIDATE_URL)) {
                    return $url;
                }
            }
        }
        return \false;
    }
}
