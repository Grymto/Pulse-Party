<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\internal;

use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractBlockable;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\HeadlessContentBlocker;
/**
 * Describe a blockable only containing `!` rules (but without `!` so it can match correctly) for `NegatePlugin`.
 * @internal
 */
class NegateBlockable extends AbstractBlockable
{
    const CRITERIA = 'negate';
    /**
     * C'tor.
     *
     * @param HeadlessContentBlocker $headlessContentBlocker
     * @param string[] $rules
     * @codeCoverageIgnore
     */
    public function __construct($headlessContentBlocker, $rules)
    {
        parent::__construct($headlessContentBlocker);
        $this->appendFromStringArray($rules);
    }
    // Documented in AbstractBlockable
    public function getBlockerId()
    {
        return 1;
    }
    // Documented in AbstractBlockable
    public function getRequiredIds()
    {
        return [];
    }
    // Documented in AbstractBlockable
    public function getCriteria()
    {
        return self::CRITERIA;
    }
}
