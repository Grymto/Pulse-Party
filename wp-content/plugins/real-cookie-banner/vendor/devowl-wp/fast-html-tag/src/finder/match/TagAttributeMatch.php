<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\TagAttributeFinder;
/**
 * Match defining a `TagAttributeFinder` match.
 * @internal
 */
class TagAttributeMatch extends AbstractMatch
{
    private $linkAttribute;
    /**
     * C'tor.
     *
     * @param TagAttributeFinder $finder
     * @param string $originalMatch
     * @param string $tag
     * @param array $attributes
     * @param string $linkAttribute
     */
    public function __construct($finder, $originalMatch, $tag, $attributes, $linkAttribute)
    {
        parent::__construct($finder, $originalMatch, $tag, $attributes);
        $this->linkAttribute = $linkAttribute;
    }
    // See `AbstractRegexFinder`.
    public function render()
    {
        return $this->encloseRendered($this->hasChanged() ? \sprintf('<%1$s%2$s%3$s', $this->getTag(), $this->renderAttributes(), $this->isSelfClosing() ? '/>' : '>') : $this->getOriginalMatch());
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getLinkAttribute()
    {
        return $this->linkAttribute;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getLink()
    {
        return $this->getAttribute($this->getLinkAttribute(), null, \true);
    }
    /**
     * Getter.
     *
     * @return TagAttributeFinder
     * @codeCoverageIgnore
     */
    public function getFinder()
    {
        return parent::getFinder();
    }
}
