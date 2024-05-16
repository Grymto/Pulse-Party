<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\scanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils;
/**
 * Describe scan options for a specific expression.
 * @internal
 */
class Rule
{
    private $blockable;
    private $expression;
    /**
     * If you want to configure group resolving, you need to configure the appropriated group via `$ruleGroups`.
     * Otherwise, defaults of the group are considered. Means: Without group configured it will be marked as
     * "one rule within the group must-be-resolved".
     *
     * @var null|string[]
     */
    private $assignedToGroups;
    /**
     * A list of query argument validations. Example:
     *
     * ```
     * [
     *      [
     *          'queryArg' => 'id',
     *          'isOptional' => true,
     *          'regExp' => '/^UA-/'
     *      ]
     * ]
     * ```
     */
    private $queryArgs;
    /**
     * The rule must be fulfilled together with a rule within its group, which does not have this flag, for the group to be resolved.
     *
     * Example:
     *
     * 1. You have embedded Google Analytics via a self hosted `https://wordpress.ci-runner-7.owlsrv.de/?local_ga_js=1` script (RankMath SEO plugin)
     * 2. Another inline script configures Google Analytics via `gtag('config', 'UA-66641795-4', {'anonymize_ip': true} );`.
     * 3. Your content blocker has a set of the following rules / expressions:
     *    - `gtag(` (Group 1)
     *    - `G-` (Group 1) (for Google Analytics v4 property)
     *    - `?local_ga-js` (Group 2).
     *
     * ```html
     * <script id="google_gtagjs" src="https://wordpress.ci-runner-7.owlsrv.de/?local_ga_js=1" async>
     * <script id="google_gtagjs-inline">
     * window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', 'UA-66641795-4', {'anonymize_ip': true} );
     * </script>
     * ```
     *
     * Now, the `gtag('js'` of Group 1 resolves the group but in real scenario, only `G-` should be considered to fulfil the group, but we do not have a string
     * `G-` in the inline-script. We can now configure the `gtag(` expression to be not part of the group validation and it does not fulfil the
     * group (i.e. not respected at validation-time).
     *
     * **Note**: If this is set to `true`, this does not modify the scan results itself, so an inline script with `<script>gtag("something")</script>`
     * is still found as scan result, but will never fulfil Group 1.
     */
    private $needsRequiredSiblingRule;
    /**
     * C'tor.
     *
     * @param ScannableBlockable $blockable
     * @param string $expression
     * @param string|string[] $assignedToGroups
     * @param array[] $queryArgs
     * @param boolean $needsRequiredSiblingRule
     * @codeCoverageIgnore
     */
    public function __construct($blockable, $expression, $assignedToGroups = [], $queryArgs = [], $needsRequiredSiblingRule = \false)
    {
        $this->blockable = $blockable;
        $this->expression = $expression;
        $this->assignedToGroups = \is_array($assignedToGroups) ? $assignedToGroups : [$assignedToGroups];
        $this->queryArgs = $queryArgs;
        $this->needsRequiredSiblingRule = $needsRequiredSiblingRule;
        $this->assignedToGroups[] = ScannableBlockable::DEFAULT_GROUP;
    }
    /**
     * Check if a given URL matches our query argument validations.
     *
     * @param string $url
     */
    public function urlMatchesQueryArgumentValidations($url)
    {
        // E.g. URLs without Scheme
        if (\filter_var(Utils::setUrlSchema($url, 'http'), \FILTER_VALIDATE_URL)) {
            $query = $this->parseUrlQueryEncodedSafe($url);
            // Remove empty values, so they get considered as null
            foreach ($query as $key => $value) {
                if (empty($value)) {
                    $query[$key] = null;
                }
            }
            foreach ($this->queryArgs as $queryKey => $queryArg) {
                $queryKey = $queryArg['queryArg'];
                $isOptional = $queryArg['isOptional'] ?? \false;
                $queryValue = $query[$queryKey] ?? null;
                if (!$isOptional && $queryValue === null) {
                    return \false;
                } elseif ($isOptional && $queryValue === null) {
                    continue;
                }
                if ($queryValue !== null) {
                    $regexp = $queryArg['regExp'] ?? null;
                    if ($regexp !== null && !\preg_match($regexp, $queryValue)) {
                        return \false;
                    }
                }
            }
            return \true;
        }
        return \false;
    }
    /**
     * In some cases, a URL could contain `&#038;` instead of `&`. This function returns the
     * query string decoded from an URL whether it is using `&` or `&#038;`.
     *
     * @param string $url
     * @param int $iteration As this function is recursively used, we need to pass the iteration so we can e.g.
     *                       avoid memory leaks when using a `$url` like `https://www.google.com/recaptcha/api.js?hl=en&ver=6.0.2#038;render=explicit`.
     *                       Why? As you can see in the URL, `#038;` is used without `&` -> falsy query args,
     *                       but should be treated as-is.
     */
    protected function parseUrlQueryEncodedSafe($url, $iteration = 0)
    {
        $queryString = \parse_url($url, \PHP_URL_QUERY);
        $query = [];
        if (!empty($queryString)) {
            $unsafeContainsString = \sprintf('?%s#038;', $queryString);
            if (\strpos($url, $unsafeContainsString) !== \false && $iteration < 2) {
                return $this->parseUrlQueryEncodedSafe(\wp_specialchars_decode($url), $iteration + 1);
            } else {
                \parse_str($queryString, $query);
            }
        }
        return $query;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getBlockable()
    {
        return $this->blockable;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getExpression()
    {
        return $this->expression;
    }
    /**
     * Getter.
     *
     * @return null|string[]
     * @codeCoverageIgnore
     */
    public function getAssignedToGroups()
    {
        return $this->assignedToGroups;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getQueryArgs()
    {
        return $this->queryArgs;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function isNeedsRequiredSiblingRule()
    {
        return $this->needsRequiredSiblingRule;
    }
}
