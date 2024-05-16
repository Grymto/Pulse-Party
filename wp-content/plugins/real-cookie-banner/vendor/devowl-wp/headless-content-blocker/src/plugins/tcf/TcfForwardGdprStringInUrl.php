<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\tcf;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\TagAttributeMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\TagAttributeFinder;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\TagAttributeMatcher;
/**
 * Read from a list of vendors and their device disclosure domains and transform URLs to TCF compatible URLs by adding
 * e.g. the `gdpr=` and `gdpr_consent=` query parameters.
 *
 * @see https://wiki.awin.com/index.php/Consent_Frameworks_for_Publishers
 * @see https://github.com/InteractiveAdvertisingBureau/GDPR-Transparency-and-Consent-Framework/blob/af2930b79f0d8d771c5b526ebf4f9c0f4ed96f53/TCFv2/Vendor%20Device%20Storage%20%26%20Operational%20Disclosures.md#domains-array
 * @internal
 */
class TcfForwardGdprStringInUrl extends AbstractPlugin
{
    const TAG_ATTRIBUTE_MAP = ['tags' => ['a', 'img'], 'attr' => ['href', 'src']];
    /**
     * List of doamins.
     *
     * @var TcfVendorDomainsBlockable[]
     */
    private $vendorDomains = [];
    /**
     * Before the content blocker gets setup.
     */
    public function setup()
    {
        $cb = $this->getHeadlessContentBlocker();
        $finder = new TagAttributeFinder(self::TAG_ATTRIBUTE_MAP['tags'], self::TAG_ATTRIBUTE_MAP['attr']);
        $finder->addCallback([$this, 'processMatch']);
        $cb->addFinder($finder);
    }
    /**
     * Process a found URL.
     *
     * @param TagAttributeMatch $match
     */
    public function processMatch($match)
    {
        $link = $match->getLink();
        if (\strpos($link, 'gdpr=') === \false) {
            $pseudoMatcher = new TagAttributeMatcher($this->getHeadlessContentBlocker());
            $blockedResult = new BlockedResult('', [], '');
            $pseudoMatcher->iterateBlockablesInString($blockedResult, $match->getLink(), \true, \false, null, $this->vendorDomains);
            if ($blockedResult->isBlocked()) {
                /**
                 * Blockable.
                 *
                 * @var TcfVendorDomainsBlockable
                 */
                $blockable = $blockedResult->getFirstBlocked();
                $newUrl = Utils::addParametersToUrl($match->getLink(), ['gdpr' => '_1TCF__GDPR_2TCF__', 'gdpr_consent' => \sprintf('_1TCF__GDPR_CONSENT_%d_2TCF__', $blockable->getVendorId())]);
                // We use `_1TCF__` and `_2TCF__` to workaround the encoding of `http_build_query` as we need to keep the `{}`
                $newUrl = \str_replace(['_1TCF__', '_2TCF__'], ['${', '}'], $newUrl);
                $match->setAttribute($match->getLinkAttribute(), $newUrl);
            }
        }
    }
    /**
     * Add a vendor with an array of domains which gets used to transform the URLs.
     *
     * @param int $vendorId
     * @param array $domains
     */
    public function addVendorDisclosureDomains($vendorId, $domains)
    {
        if (!\is_array($domains)) {
            return;
        }
        $this->vendorDomains[] = new TcfVendorDomainsBlockable($this->getHeadlessContentBlocker(), $vendorId, $domains);
    }
}
