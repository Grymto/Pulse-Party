<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\scanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
/**
 * Put in a list of `ScanEntry`'s and sort out false-positives and deduplicate. Keep
 * in mind, that this processor can also touch your `ScanEntry` properties as well!
 * @internal
 */
class FalsePositivesProcessor
{
    private $blockableScanner;
    private $entries;
    /**
     * C'tor.
     *
     * @param BlockableScanner $blockableScanner
     * @param ScanEntry[] $entries
     * @codeCoverageIgnore
     */
    public function __construct($blockableScanner, $entries)
    {
        $this->blockableScanner = $blockableScanner;
        $this->entries = $entries;
    }
    /**
     * Prepare the passed results and do some optimizations on them (e.g. remove duplicates).
     */
    public function process()
    {
        $this->convertTemplatesWithNonMatchingGroupsToExternalUrl();
        $this->deduplicate();
        $this->convertExternalUrlsCoveredByTemplate();
        $this->convertStandaloneLinkRelTemplateToExternalUrl();
        $this->removeExternalUrlsWithTemplateDuplicate();
        $this->removeDuplicateScannedItems();
        return $this->getEntries();
    }
    /**
     * Example: We have the following markup:
     *
     * ```
     * <link rel="stylesheet" id="everest-forms-google-fonts-css" href="https://fonts.googleapis.com/css?family=Josefin+Sans&#038;ver=1.1.6" />
     * ```
     *
     * And a content blocker for everest forms with the following rules:
     *
     * ```
     * *fonts.googleapis.com*
     * link[id="everest-forms-google-fonts-css"]
     * ```
     *
     * This would lead to duplicate entries in the scanner result list. Never show double-scanned elements when they e.g. caught by two rules
     * and a rerun through `Utils::preg_replace_callback_recursive`. In this case, we will modify the already existing scan entries' found expressions.
     */
    public function removeDuplicateScannedItems()
    {
        $this->blockableScanner->setActive(\false);
        $contentBlocker = $this->blockableScanner->getHeadlessContentBlocker();
        foreach ($this->entries as $idx => $scanEntry) {
            if (!empty($scanEntry->template) && $scanEntry->markup !== null && \strpos($scanEntry->markup->getContent(), \sprintf('%s="%s"', Constants::HTML_ATTRIBUTE_BY, 'scannable')) !== \false) {
                $previousBlockables = $contentBlocker->getBlockables();
                $contentBlocker->setBlockables([$scanEntry->blockable]);
                foreach ($this->entries as $anotherEntry) {
                    if ($anotherEntry !== $scanEntry && $scanEntry->template === $anotherEntry->template && $anotherEntry->markup !== null && \strpos($anotherEntry->markup->getContent(), \sprintf('%s="%s"', Constants::HTML_ATTRIBUTE_BY, 'scannable')) === \false) {
                        // Check if this blockable matches the "original" first found item
                        $markup = $contentBlocker->modifyAny($anotherEntry->markup->getContent());
                        if (\preg_match(\sprintf('/%s="([^"]+)"/m', Constants::HTML_ATTRIBUTE_BLOCKER_ID), $markup, $consentIdMatches)) {
                            $anotherEntry->expressions = \array_values(\array_unique(\array_merge($anotherEntry->expressions, $scanEntry->expressions)));
                            // Copy some fields which could be interesting for the first found scan entry, too
                            foreach (['blocked_url', 'source_url'] as $copyAttr) {
                                if (empty($anotherEntry->{$copyAttr})) {
                                    $anotherEntry->{$copyAttr} = $scanEntry->{$copyAttr};
                                }
                            }
                        }
                    }
                }
                $contentBlocker->setBlockables($previousBlockables);
                unset($this->entries[$idx]);
            }
        }
        // Reset indexes
        $this->blockableScanner->setActive(\false);
        $this->entries = \array_values($this->entries);
    }
    /**
     * Remove external URLs which are duplicated as template, too.
     */
    public function removeExternalUrlsWithTemplateDuplicate()
    {
        $remove = [];
        foreach ($this->entries as $scanEntry) {
            if ($scanEntry->markup && empty($scanEntry->template)) {
                foreach ($this->entries as $anotherEntry) {
                    if ($anotherEntry !== $scanEntry && !empty($anotherEntry->template) && $anotherEntry->markup && $anotherEntry->markup->getId() === $scanEntry->markup->getId()) {
                        $remove[] = $scanEntry;
                    }
                }
            }
        }
        foreach ($this->entries as $key => $value) {
            if (\in_array($value, $remove, \true)) {
                unset($this->entries[$key]);
            }
        }
        // Reset indexes
        $this->entries = \array_values($this->entries);
    }
    /**
     * Deduplicate coexisting templates. Examples:
     *
     * - CF7 with reCaptcha over Google reCaptcha
     * - MonsterInsights > Google Analytics (`extended`)
     */
    public function deduplicate()
    {
        $removeByIdentifier = [];
        foreach ($this->entries as $key => $value) {
            $foundBetterTemplate = $this->alreadyExistsInOtherFoundTemplate($value);
            if ($foundBetterTemplate !== \false) {
                unset($this->entries[$key]);
                continue;
            }
            // Scenario: MonsterInsights > Google Analytics
            $blockable = $value->blockable ?? null;
            if (\is_null($blockable)) {
                continue;
            }
            $extended = $blockable->getExtended();
            if (!\is_null($extended)) {
                $removeByIdentifier[] = $extended;
                continue;
            }
        }
        foreach ($this->entries as $key => $value) {
            if (\in_array($value->template, $removeByIdentifier, \true)) {
                unset($this->entries[$key]);
            }
        }
        // Reset indexes
        $this->entries = \array_values($this->entries);
    }
    /**
     * Remove all entries when there is not a scan entry with the needed host and convert it to an external URL.
     */
    public function convertTemplatesWithNonMatchingGroupsToExternalUrl()
    {
        $remove = [];
        $resetTemplates = [];
        foreach ($this->entries as $key => $scanEntry) {
            if (!isset($scanEntry->template)) {
                continue;
            }
            $blockable = $scanEntry->blockable ?? null;
            if (\is_null($blockable)) {
                continue;
            }
            // Collect all found host expressions for this template
            $foundExpressions = [];
            foreach ($this->entries as $anotherEntry) {
                if ($anotherEntry->template === $scanEntry->template) {
                    foreach ($anotherEntry->expressions as $foundExpression) {
                        // Exclude found expressions when it does not match with query validation
                        $rules = $blockable->getRulesByExpression($foundExpression);
                        foreach ($rules as $rule) {
                            if (empty($rule->getAssignedToGroups())) {
                                continue;
                            }
                            if (!empty($anotherEntry->blocked_url) && !$rule->urlMatchesQueryArgumentValidations($anotherEntry->blocked_url)) {
                                continue;
                            }
                            foreach ($rule->getAssignedToGroups() as $group) {
                                $foundExpressions[$group][] = $foundExpression;
                            }
                        }
                    }
                }
            }
            if (!$blockable->checkExpressionsMatchesGroups($foundExpressions)) {
                if (!empty($scanEntry->blocked_url) && $this->blockableScanner->isNotAnExcludedUrl($scanEntry->blocked_url) && !$this->canExternalUrlBeBypassed($scanEntry)) {
                    $resetTemplates[] = $scanEntry;
                    $scanEntry->lock = \true;
                } else {
                    $remove[] = $scanEntry;
                }
            }
        }
        // Lazily reset templates and remove non-URL items so above calculations can calculate with original items
        foreach ($this->entries as $key => $value) {
            if (\in_array($value, $remove, \true)) {
                unset($this->entries[$key]);
            } elseif (\in_array($value, $resetTemplates, \true)) {
                $this->entries[$key]->template = '';
            }
        }
        // Reset indexes
        $this->entries = \array_values($this->entries);
    }
    /**
     * Convert external URLs which got covered by a template. When is this the case? When using a
     * `SelectorSyntaxBlocker` with e.g. `link[href=""]` (for example WordPress emojis).
     *
     * @param ScanEntry[] $entries The entries to check, defaults to current instance entries
     */
    public function convertExternalUrlsCoveredByTemplate($entries = null)
    {
        // Remove all not-found templates as we want to only remove by found template
        $foundTemplateIds = \array_values(\array_unique(\array_column($this->entries, 'template')));
        $contentBlocker = $this->blockableScanner->getHeadlessContentBlocker();
        $previousBlockables = $contentBlocker->getBlockables();
        $contentBlocker->setBlockables(\array_filter($previousBlockables, function ($blockable) use($foundTemplateIds) {
            if ($blockable instanceof ScannableBlockable) {
                return \in_array($blockable->getIdentifier(), $foundTemplateIds, \true);
            }
            return \true;
        }));
        foreach ($this->entries as $entry) {
            if ($entries !== null && !\in_array($entry, $entries, \true)) {
                continue;
            }
            $markup = $entry->markup;
            if ($markup !== null && !empty($entry->tag) && !empty($entry->blocked_url) && empty($entry->template) && !$entry->lock) {
                $markup = $contentBlocker->modifyAny($markup->getContent());
                if (\preg_match(\sprintf('/%s="([^"]+)"/m', Constants::HTML_ATTRIBUTE_BLOCKER_ID), $markup, $consentIdMatches)) {
                    $entry->template = $consentIdMatches[1];
                }
            }
        }
        $contentBlocker->setBlockables($previousBlockables);
        // Reset indexes
        $this->entries = \array_values($this->entries);
    }
    /**
     * Convert a found `link[rel="preconnect|dns-prefetch"]` within a template and stands alone within this template
     * to an external URL as a DNS-prefetch and preconnect **must** be loaded in conjunction with another script.
     */
    public function convertStandaloneLinkRelTemplateToExternalUrl()
    {
        /**
         * Scan entries.
         *
         * @var ScanEntry[]
         */
        $convert = [];
        foreach ($this->entries as $key => $scanEntry) {
            $markup = $scanEntry->markup;
            if (!isset($scanEntry->template) || \in_array($scanEntry->template, $convert, \true) || !isset($markup)) {
                continue;
            }
            $markup = $markup->getContent();
            if ($scanEntry->tag === 'link' && (\strpos($markup, 'dns-prefetch') !== \false || \strpos($markup, 'preconnect') !== \false)) {
                // Collect all found scan entries for this template
                $foundEntriesForThisTemplate = [$scanEntry];
                foreach ($this->entries as $anotherEntry) {
                    if ($anotherEntry !== $scanEntry && $anotherEntry->template === $scanEntry->template) {
                        $foundEntriesForThisTemplate[] = $anotherEntry;
                    }
                }
                if (\count($foundEntriesForThisTemplate) === 1) {
                    $convert[] = $scanEntry;
                }
            }
        }
        if (\count($convert)) {
            $added = [];
            foreach ($convert as $convertScanEntry) {
                $key = \array_search($convertScanEntry, $this->entries, \true);
                $this->entries[] = $added[] = $entry = new ScanEntry();
                $entry->blocked_url = $convertScanEntry->blocked_url;
                $entry->tag = $convertScanEntry->tag;
                $entry->attribute = $convertScanEntry->attribute;
                $entry->markup = $convertScanEntry->markup;
                unset($this->entries[$key]);
            }
            // Check again for the external URLs as they can indeed have links covered by other templates
            $this->convertExternalUrlsCoveredByTemplate($added);
        }
    }
    /**
     * Check if a given template already exists in another scan result.
     *
     * @param ScanEntry $scanEntry
     * @return false|ScanEntry The found entry which better suits this template
     */
    protected function alreadyExistsInOtherFoundTemplate($scanEntry)
    {
        $blockable = $scanEntry->blockable ?? null;
        if (\is_null($blockable)) {
            return \false;
        }
        foreach ($this->entries as $existing) {
            if ($existing !== $scanEntry && isset($existing->blockable) && !empty($existing->template)) {
                $currentHosts = $blockable->getOriginalExpressions();
                $existingHosts = $existing->blockable->getOriginalExpressions();
                if (\count($existingHosts) > \count($currentHosts)) {
                    // Only compare when our opposite scan entry has more hosts to block
                    // This avoids to alert false-positives when using `extends` middleware
                    $foundSame = 0;
                    foreach ($currentHosts as $currentHost) {
                        if (\in_array($currentHost, $existingHosts, \true)) {
                            ++$foundSame;
                        }
                    }
                    if ($foundSame === \count($currentHosts)) {
                        return $existing;
                    }
                }
            }
        }
        return \false;
    }
    /**
     * Example: A blocked form does not have reCAPTCHA, got found as "CleverReach". The `form[action]` does
     * not need to get blocked due to the fact the server is only contacted through submit-interaction (a privacy
     * policy needs to be linked / checkbox).
     *
     * @param ScanEntry $entry
     */
    public function canExternalUrlBeBypassed($entry)
    {
        if ($entry->blocked_url !== null && $entry->tag === 'form' && $entry->attribute === 'action') {
            return \true;
        }
        return \false;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getEntries()
    {
        return $this->entries;
    }
}
