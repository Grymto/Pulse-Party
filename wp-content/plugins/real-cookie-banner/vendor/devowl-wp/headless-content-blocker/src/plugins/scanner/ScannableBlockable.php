<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\scanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractBlockable;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\HeadlessContentBlocker;
/**
 * Describe a blockable item.
 * @internal
 */
class ScannableBlockable extends AbstractBlockable
{
    /**
     * Every rule is within this group.
     */
    const DEFAULT_GROUP = '__default__';
    private $identifier;
    private $extended;
    /**
     * Each rule group gets an own instance of `RuleGroup`.
     *
     * @var RuleGroup[]
     */
    private $ruleGroups = [];
    /**
     * Each expression gets an own instance of `Rule`.
     *
     * @var Rule[]
     */
    private $rules = [];
    // See `addRules`
    private $currentlyAddingRules = \false;
    /**
     * C'tor.
     *
     * Example array for `$rules`:
     *
     * ```
     * [
     *     [
     *          'expression' => "*google.com/recaptcha*",
     *          'assignedToGroups' => 'script', // can be string[]
     *          'queryArgs' => [
     *               [
     *                   'queryArg' => 'id',
     *                   'isOptional' => true,
     *                   'regExp' => '/^UA-/'
     *               ]
     *          ],
     *          'needsRequiredSiblingRule' => false
     *     ]
     * ]
     * ```
     *
     * @param HeadlessContentBlocker $headlessContentBlocker
     * @param string $identifier
     * @param string $extended The parent extended template identifier
     * @param Rule[]|array[] $rules A list of expressions which hold different scan options; you can also pass
     *                              an array which gets automatically converted to `Rule`.
     * @param RuleGroup[]|array[] $ruleGroups You can also pass an array which gets automatically converted to `RuleGroup`.
     * @codeCoverageIgnore
     */
    public function __construct($headlessContentBlocker, $identifier, $extended = null, $rules = [], $ruleGroups = [])
    {
        parent::__construct($headlessContentBlocker);
        $this->identifier = $identifier;
        $this->extended = $extended;
        $this->addRules($rules, $ruleGroups);
        $this->ruleGroups[self::DEFAULT_GROUP] = new RuleGroup($this, self::DEFAULT_GROUP, \false, \true);
    }
    // Documented in AbstractBlockable
    public function appendFromStringArray($blockers)
    {
        parent::appendFromStringArray($blockers);
        if (!$this->currentlyAddingRules) {
            $this->addRules($blockers, [], \false);
        }
    }
    /**
     * Allows to add rules and rule groups dynamically.
     *
     * @param Rule[]|array[]|string[] $rules A list of expressions which hold different scan options; you can also pass
     *                              an array which gets automatically converted to `Rule`.
     * @param RuleGroup[]|array[] $ruleGroups You can also pass an array which gets automatically converted to `RuleGroup`.
     * @param boolean $appendFromStringArray Run `$this->appendFromStringArray()` for the added rules
     */
    public function addRules($rules, $ruleGroups = [], $appendFromStringArray = \true)
    {
        // Create rule groups
        foreach ($ruleGroups as $ruleGroup) {
            if (\is_array($ruleGroup)) {
                $this->ruleGroups[$ruleGroup['id']] = new RuleGroup($this, $ruleGroup['id'], $ruleGroup['mustAllRulesBeResolved'] ?? \false, $ruleGroup['mustGroupBeResolved'] ?? \true);
            } elseif ($ruleGroup instanceof RuleGroup) {
                $this->ruleGroups[$ruleGroup['id']] = $ruleGroup;
            }
        }
        // Create host rule instances
        foreach ($rules as $rule) {
            if (\is_array($rule)) {
                $newRule = new Rule($this, $rule['expression'], $rule['assignedToGroups'] ?? [], $rule['queryArgs'] ?? [], $rule['needsRequiredSiblingRule'] ?? \false);
            } elseif ($rule instanceof Rule) {
                $newRule = $rule;
            } elseif (\is_string($rule)) {
                $newRule = new Rule($this, $rule);
            } else {
                continue;
            }
            // Create rule group if not yet existing
            $this->rules[] = $newRule;
            foreach ($newRule->getAssignedToGroups() as $assignedToGroup) {
                if (!isset($this->ruleGroups[$assignedToGroup])) {
                    $this->ruleGroups[$assignedToGroup] = new RuleGroup($this, $assignedToGroup);
                }
            }
        }
        // Create the list of expressions
        $expressions = [];
        foreach ($this->rules as $rule) {
            $expressions[] = $rule->getExpression();
        }
        if ($appendFromStringArray) {
            $this->currentlyAddingRules = \true;
            $this->appendFromStringArray($expressions);
            $this->currentlyAddingRules = \false;
        }
    }
    // Documented in AbstractBlockable
    public function getBlockerId()
    {
        // This is only used for scanning purposes!
        return $this->getIdentifier();
    }
    // Documented in AbstractBlockable
    public function getRequiredIds()
    {
        return [];
    }
    // Documented in AbstractBlockable
    public function getCriteria()
    {
        return 'scannable';
    }
    /**
     * Check if a set of expressions matches at least one in our configured groups.
     *
     * It returns only `true` when all configured groups got resolved.
     *
     * @param string[][] $expressionsMap
     */
    public function checkExpressionsMatchesGroups($expressionsMap)
    {
        // A map of group name and count of matched expressions and group expressions
        $result = [];
        // Check if there is a rule which is not assigned to any rule, as it skips this check completely
        // not implemented like this, as e.g. the youtube template does not define a group and default of
        // `mustGroupBeResolved = false`.
        /*foreach ($expressionsMap as $expressions) {
              foreach ($expressions as $expression) {
                  foreach ($this->rules as $rule) {
                      if ($rule->getExpression() === $expression && empty($rule->getAssignedToGroups())) {
                          return true;
                      }
                  }
              }
          }*/
        // Calculate current result
        foreach ($this->getGroupsWithExpressionsMap() as $groupName => $groupExpressions) {
            $expressions = \array_values(\array_unique($expressionsMap[$groupName] ?? []));
            $result[$groupName] = [\count(\array_intersect($groupExpressions, $expressions)), \count($groupExpressions)];
        }
        // Check for rule groups validitity
        foreach ($this->ruleGroups as $groupName => $group) {
            $resultRow = $result[$groupName] ?? [0, 0];
            if ($group->isMustAllRulesBeResolved() && $resultRow[0] > 0 && $resultRow[0] < $resultRow[1]) {
                return \false;
            }
            if ($group->isMustGroupBeResolved() && $resultRow[0] === 0) {
                return \false;
            }
        }
        return \true;
    }
    /**
     * Get a map of `Record<string (group), string[] (expressions)>`.
     */
    public function getGroupsWithExpressionsMap()
    {
        $result = [];
        foreach ($this->rules as $rule) {
            if ($rule->isNeedsRequiredSiblingRule()) {
                continue;
            }
            $assignedToGroups = $rule->getAssignedToGroups();
            foreach ($assignedToGroups as $group) {
                if (!isset($result[$group])) {
                    $result[$group] = [];
                }
                $result[$group][] = $rule->getExpression();
            }
        }
        return $result;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getExtended()
    {
        return $this->extended;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getRules()
    {
        return $this->rules;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getRuleGroups()
    {
        return $this->ruleGroups;
    }
    /**
     * Getter.
     *
     * @param string $expression
     * @return Rule[]
     * @codeCoverageIgnore
     */
    public function getRulesByExpression($expression)
    {
        $result = [];
        foreach ($this->rules as $rule) {
            if ($rule->getExpression() === $expression) {
                $result[] = $rule;
            }
        }
        return $result;
    }
}
