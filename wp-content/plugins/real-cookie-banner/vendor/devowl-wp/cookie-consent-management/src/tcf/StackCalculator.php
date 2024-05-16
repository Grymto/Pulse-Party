<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\tcf;

/**
 * Quote from docs: Stacks may be used to substitute Initial Layer information about two or more Purposes
 * and/or Special Features (also see Appendix B). Purposes must not be included in more than one Stack,
 * and must not be presented as part of a Stack and outside of Stacks at the same time. Conversely,
 * any Stacks used must not include the same Purpose more than once, nor include Purposes presented separately from Stacks.
 *
 * @see https://iabeurope.eu/iab-europe-transparency-consent-framework-policies/#___E_Stacks__
 * @internal
 */
class StackCalculator
{
    const DECLARATION_TYPE_PURPOSES = 'purposes';
    const DECLARATION_TYPE_SPECIAL_PURPOSES = 'specialPurposes';
    const DECLARATION_TYPE_FEATURES = 'features';
    const DECLARATION_TYPE_SPECIAL_FEATURES = 'specialFeatures';
    const DECLARATION_TYPE_DATA_CATEGORIES = 'dataCategories';
    const DECLARATION_TYPES = [self::DECLARATION_TYPE_PURPOSES, self::DECLARATION_TYPE_SPECIAL_PURPOSES, self::DECLARATION_TYPE_FEATURES, self::DECLARATION_TYPE_SPECIAL_FEATURES, self::DECLARATION_TYPE_DATA_CATEGORIES];
    const STACK_DECLARATIONS = [self::DECLARATION_TYPE_PURPOSES, self::DECLARATION_TYPE_SPECIAL_FEATURES];
    /**
     * Clone stacks so we can obtain the original object by ID for the result.
     */
    private $originalStacks;
    private $stacks;
    private $usedDeclarations;
    private $scoreMap = [];
    /**
     * See class description.
     *
     * @param array $stacks A list of all available stacks (see `Query::stacks`)
     * @param array $usedDeclarations List of used declarations as map (`purposes` and `specialPurposes`)
     * @codeCoverageIgnore
     */
    public function __construct($stacks, $usedDeclarations)
    {
        $this->originalStacks = $stacks;
        $this->stacks = $stacks;
        $this->usedDeclarations = $usedDeclarations;
        $this->scoring();
    }
    /**
     * Return best suitable stacks for the current used declarations.
     */
    public function calculateBestSuitableStacks()
    {
        $usedDeclarationsToStack = $this->usedDeclarationToStack();
        // Uniqueness the used stacks
        $calculated = [];
        foreach (self::STACK_DECLARATIONS as $declaration) {
            $calculated = \array_unique(\array_merge($calculated, \array_values($usedDeclarationsToStack[$declaration] ?? [])));
        }
        // We have now all used stack IDs, let's return it as objects instead of IDs
        $result = [];
        foreach ($calculated as $val) {
            foreach ($this->originalStacks as $stack) {
                if ($stack['id'] === $val) {
                    $result[$stack['id']] = \array_merge($stack, $this->scoreMap[$stack['id']]);
                    break;
                }
            }
        }
        // Sort again by score so the "best" stack is on top
        $this->sortByScore($result, \true);
        return $result;
    }
    /**
     * Get best suitable stack for all our used purposes.
     */
    protected function usedDeclarationToStack()
    {
        $usedDeclarationsToStack = [];
        foreach (self::STACK_DECLARATIONS as $declaration) {
            foreach ($this->usedDeclarations[$declaration] as $declarationId) {
                $found = null;
                foreach ($this->stacks as $stack) {
                    if (\in_array($declarationId, $stack[$declaration], \true)) {
                        $found = $stack;
                        break;
                    }
                }
                if ($found !== null) {
                    $usedDeclarationsToStack[$declaration][$declarationId] = $found['id'];
                }
            }
        }
        return $usedDeclarationsToStack;
    }
    /**
     * Rate each stack how many purposes and special features suit our used
     * purposes, and remove unused stacks.
     */
    protected function scoring()
    {
        // Rate each stack how many purposes and special features suit our used purposes, and remove unused stacks
        foreach ($this->stacks as $stackId => &$stack) {
            $possibleScore = 0;
            $score = 0;
            foreach (self::STACK_DECLARATIONS as $declaration) {
                $possibleScore += \count($stack[$declaration]);
                foreach ($stack[$declaration] as $declarationId) {
                    if (\in_array($declarationId, $this->usedDeclarations[$declaration], \true)) {
                        ++$score;
                    }
                }
            }
            $stack['possibleScore'] = $possibleScore;
            $stack['score'] = $score;
            $this->scoreMap[$stackId] = ['possibleScore' => $possibleScore, 'score' => $score];
        }
        $this->sortByScore($this->stacks);
    }
    /**
     * Sort a given array of stacks by score.
     *
     * @param array $stacks
     * @param boolean $truncateScores If `true`, the score fields will be removed from the array
     */
    protected function sortByScore(&$stacks, $truncateScores = \false)
    {
        \array_multisort(\array_column($stacks, 'score'), \SORT_DESC, \array_column($stacks, 'possibleScore'), \SORT_ASC, $stacks);
        if ($truncateScores) {
            foreach ($stacks as &$stack) {
                unset($stack['possibleScore']);
                unset($stack['score']);
            }
        }
    }
}
