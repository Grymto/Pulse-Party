<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\ScriptInlineMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\AbstractMatcher;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\ScriptInlineMatcher;
/**
 * Check if an inline script is a localized script via e.g. `wp_set_script_translations` and
 * check if the JSON has blocked content.
 *
 * @see https://app.clickup.com/t/2my9x5r
 * @see https://regex101.com/r/HlYVFE/4
 * @internal
 */
class ScriptInlineJsonBlocker extends AbstractPlugin
{
    private $schemas = [];
    /**
     * See `AbstractPlugin`.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function checkResult($result, $matcher, $match)
    {
        if ($matcher instanceof ScriptInlineMatcher) {
            /**
             * Var.
             *
             * @var ScriptInlineMatch
             */
            $match = $match;
            foreach ($this->schemas as $schema) {
                $modifiedScript = $this->replaceScriptJsonContent($match, $schema[0], $schema[1], $modified, $schema[2]);
                if ($modified !== \false) {
                    $result->disableBlocking();
                    if ($modified === 1) {
                        $match->setScript($modifiedScript);
                    }
                }
            }
        }
        return $result;
    }
    /**
     * See class description.
     *
     * @param string $strpos Simple contains check before doing extensive preg replace
     * @param string $pattern Pattern which results in a JSON string. The pattern needs to expose three capturing groups,
     *                        whereas the first and the third represent begin and end, and the second one is the "JSON string" between.
     * @param string $appendToScript
     * @see https://app.clickup.com/t/2my9x5r
     * @see https://regex101.com/r/HlYVFE/4
     */
    public function addSchema($strpos, $pattern, $appendToScript = '')
    {
        $this->schemas[] = [$strpos, $pattern, $appendToScript];
    }
    /**
     * See class description.
     *
     * @param ScriptInlineMatch $match
     * @param string $strpos Simple contains check before doing extensive preg replace
     * @param string $pattern Pattern which results in a JSON string
     * @param boolean|int $result `$false` if it is not a script matching the pattern, `0` when there is no blocked content found within
     *                           the JSON object, and `1` if content got blocked in JSON values.
     * @param string $appendToScript
     * @see https://app.clickup.com/t/2my9x5r
     * @see https://regex101.com/r/HlYVFE/4
     */
    public function replaceScriptJsonContent($match, $strpos, $pattern, &$result = null, $appendToScript = '')
    {
        $result = \false;
        if (\strpos($match->getScript(), $strpos) === \false) {
            return $result;
        }
        $modifiedScript = Utils::preg_jit_safe($pattern, function ($pattern) use($match, &$result, $appendToScript) {
            return \preg_replace_callback($pattern, function ($m) use(&$result) {
                $json = Utils::isJson($m[2]);
                if ($json) {
                    $json = \json_encode($json);
                    // Decode and encode to unify white-spaces
                    $modifiedJson = $this->getHeadlessContentBlocker()->modifyAny($json);
                    $isModified = $json !== $modifiedJson;
                    $result = $isModified ? 1 : 0;
                    if ($result) {
                        return \sprintf('%s%s%s', $m[1], $modifiedJson, $m[3]);
                    }
                }
                return $m[0];
            }, $match->getScript() . $appendToScript);
        });
        return \substr($modifiedScript, 0, \strlen($modifiedScript) - \strlen($appendToScript));
        // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd
}
