<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher;

use DevOwl\RealCookieBanner\Vendor\Sabberworm\CSS\CSSList\CSSBlockList;
use DevOwl\RealCookieBanner\Vendor\Sabberworm\CSS\CSSList\CSSList;
use DevOwl\RealCookieBanner\Vendor\Sabberworm\CSS\CSSList\Document;
use DevOwl\RealCookieBanner\Vendor\Sabberworm\CSS\OutputFormat;
use DevOwl\RealCookieBanner\Vendor\Sabberworm\CSS\Property\Import;
use DevOwl\RealCookieBanner\Vendor\Sabberworm\CSS\Renderable;
use DevOwl\RealCookieBanner\Vendor\Sabberworm\CSS\RuleSet\AtRuleSet;
use DevOwl\RealCookieBanner\Vendor\Sabberworm\CSS\RuleSet\DeclarationBlock;
use DevOwl\RealCookieBanner\Vendor\Sabberworm\CSS\RuleSet\RuleSet;
use DevOwl\RealCookieBanner\Vendor\Sabberworm\CSS\Value\URL;
/**
 * Helper functionality for CSS documents.
 * @internal
 */
class CssHelper
{
    /**
     * Remove blanks from a CSS List.
     *
     * @param CSSList $oList
     * @see https://git.io/JY5er
     */
    public static function removeBlanksFromCSSList($oList)
    {
        if ($oList === null) {
            return;
        }
        foreach ($oList->getContents() as $oBlock) {
            if ($oBlock instanceof DeclarationBlock) {
                if (empty($oBlock->getRules())) {
                    $oList->remove($oBlock);
                }
            } elseif ($oBlock instanceof CSSBlockList) {
                self::removeBlanksFromCSSList($oBlock);
                if (empty($oBlock->getContents())) {
                    $oList->remove($oBlock);
                }
            } elseif ($oBlock instanceof AtRuleSet) {
                if (empty($oBlock->getRules())) {
                    $oList->remove($oBlock);
                }
            }
        }
    }
    /**
     * `strpos` two given values from our CSS Document.
     *
     * @param string|Renderable $haystack The string to search in
     * @param string|Renderable $needle The searched string
     */
    public static function strposValues($haystack, $needle)
    {
        $haystackString = self::renderableString($haystack);
        $needleString = self::renderableString($needle);
        return \strpos($haystackString, $needleString);
    }
    /**
     * Probably `render` a `Renderable`.
     *
     * @param string|Renderable $renderable
     */
    public static function renderableString($renderable)
    {
        return \is_string($renderable) ? $renderable : $renderable->render(self::getCompactOutputFormat());
    }
    /**
     * Get the compact output format for our parser.
     */
    public static function getCompactOutputFormat()
    {
        static $outputFormat = null;
        if ($outputFormat === null) {
            $outputFormat = OutputFormat::createCompact();
        }
        return $outputFormat;
    }
    /**
     * Remove a given CSS value from a given document and return the removed elements.
     *
     * @param mixed $value
     * @param Document $document
     */
    public static function removeValueFromDocument($value, $document)
    {
        if ($document->remove($value)) {
            return [$value];
        } else {
            $found = [];
            foreach ($document->getAllRuleSets() as $ruleSet) {
                /**
                 * RuleSet
                 *
                 * @var RuleSet
                 */
                $ruleSet = $ruleSet;
                foreach ($ruleSet->getRules() as $rule) {
                    if (self::strposValues($rule->getValue(), $value) !== \false) {
                        $ruleSet->removeRule($rule);
                        $found[] = $rule->getValue();
                        break;
                    }
                }
            }
            return $found;
        }
    }
    /**
     * Apply URLs changes to document.
     *
     * @param array $setUrlChanges Result of `StyleInlineMatcher::generateLocationChangeSet`
     * @param Document $document
     */
    public static function applyLocationChangeSet($setUrlChanges, $document)
    {
        $outputFormat = self::getCompactOutputFormat();
        foreach ($setUrlChanges as $change) {
            $renderedChangeLocation = $change[0]->render($outputFormat);
            foreach ($document->getAllValues() as $value) {
                /**
                 * The found URL instance.
                 *
                 * @var URL
                 */
                $location = null;
                if ($value instanceof Import) {
                    $location = $value->getLocation();
                } elseif ($value instanceof URL) {
                    $location = $value;
                }
                if ($location !== null && $location->render($outputFormat) === $renderedChangeLocation) {
                    $location->setURL($change[1]);
                }
            }
        }
    }
    /**
     * Remove all non-blocked rules depending on a "removal" list.
     *
     * @param Document $document
     * @param array $removedFromOriginalDocument
     * @param RuleSet[] $removedRuleSetsFromOriginalDocument
     */
    public static function removeNonBlockedRulesFromDocument($document, $removedFromOriginalDocument, $removedRuleSetsFromOriginalDocument)
    {
        // Save computation time by calculating removed values only once
        $removedFromOriginalDocumentRenderedStringMap = [];
        foreach ($removedFromOriginalDocument as $removed) {
            $removedFromOriginalDocumentRenderedStringMap[] = self::renderableString($removed);
        }
        // Save computation time by calculating removed rules only once
        $removedRuleSetsFromOriginalDocumentRenderedStringMap = [];
        foreach ($removedRuleSetsFromOriginalDocument as $removedRuleSet) {
            $removedRuleSetsFromOriginalDocumentRenderedStringMap[] = self::renderableString($removedRuleSet);
        }
        // Remove all non-blocked rules from second inline style
        foreach ($document->getAllRuleSets() as $ruleSet) {
            // Check if the complete rule can be removed
            $found = \false;
            $ruleSetString = self::renderableString($ruleSet);
            foreach ($removedRuleSetsFromOriginalDocumentRenderedStringMap as $removedRuleSet) {
                if (self::strposValues($removedRuleSet, $ruleSetString) !== \false) {
                    $found = \true;
                    break;
                }
            }
            if ($found) {
                continue;
            }
            /**
             * RuleSet
             *
             * @var RuleSet
             */
            $ruleSet = $ruleSet;
            foreach ($ruleSet->getRules() as $rule) {
                $found = \false;
                foreach ($removedFromOriginalDocumentRenderedStringMap as $value) {
                    if (self::strposValues($rule->getValue(), $value) !== \false) {
                        $found = \true;
                        break;
                    }
                }
                if (!$found) {
                    $ruleSet->removeRule($rule);
                }
            }
        }
        // Also try to remove all `@import`'s
        foreach ($document->getAllValues() as $value) {
            $found = \false;
            foreach ($removedFromOriginalDocumentRenderedStringMap as $removedValue) {
                if (self::strposValues($removedValue, $value) !== \false) {
                    $found = \true;
                    break;
                }
            }
            if (!$found) {
                $document->remove($value);
            }
        }
    }
}
