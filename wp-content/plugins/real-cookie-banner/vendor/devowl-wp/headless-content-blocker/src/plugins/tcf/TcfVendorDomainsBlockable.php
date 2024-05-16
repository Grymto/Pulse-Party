<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\tcf;

use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractBlockable;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\HeadlessContentBlocker;
/**
 * Describe an item for `TcfForwardGdprStringInUrl`.
 * @internal
 */
class TcfVendorDomainsBlockable extends AbstractBlockable
{
    const SKIP_DOMAINS = [
        // This modifies all available links, so skip it
        '*',
    ];
    private $vendorId;
    /**
     * C'tor.
     *
     * @param HeadlessContentBlocker $headlessContentBlocker
     * @param int $vendorId
     * @param array $domains
     * @codeCoverageIgnore
     */
    public function __construct($headlessContentBlocker, $vendorId, $domains)
    {
        parent::__construct($headlessContentBlocker);
        $this->vendorId = $vendorId;
        foreach ($domains as $row) {
            if (\is_array($row) && !\in_array($row['domain'], self::SKIP_DOMAINS, \true)) {
                $this->appendFromStringArray([$row['domain']]);
            }
        }
    }
    // Documented in AbstractBlockable
    public function getBlockerId()
    {
        return '';
    }
    // Documented in AbstractBlockable
    public function getRequiredIds()
    {
        return [];
    }
    // Documented in AbstractBlockable
    public function getCriteria()
    {
        return 'none';
    }
    /**
     * Getter.
     */
    public function getVendorId()
    {
        return $this->vendorId;
    }
}
