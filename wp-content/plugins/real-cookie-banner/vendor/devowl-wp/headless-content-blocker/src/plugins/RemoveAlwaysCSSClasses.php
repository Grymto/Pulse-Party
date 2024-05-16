<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AttributesHelper;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\AbstractMatcher;
/**
 * Allows to remove specific CSS classes from `class` definition without touching
 * others. This is useful for e.g. lazy-loading libraries.
 * @internal
 */
class RemoveAlwaysCSSClasses extends AbstractPlugin
{
    /**
     * Compatibility with lazy loading libraries like `lazysizes`.
     *
     * @see https://github.com/aFarkas/lazysizes
     */
    const KNOWN_LAZY_LOADED_CLASSES = ['lazyload', 'lazy', 'lazy-hidden'];
    private $classNames = [];
    /**
     * See `AbstractPlugin`.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function blockedMatch($result, $matcher, $match)
    {
        if ($match->hasAttribute('class')) {
            $transform = AttributesHelper::transformAttribute('class');
            $originalClasses = $match->getAttribute('class');
            $currentClasses = \explode(' ', $originalClasses);
            $foundCount = 0;
            foreach ($this->classNames as $className) {
                $found = \array_search($className, $currentClasses, \true);
                if ($found !== \false) {
                    // Remove from our class itself
                    unset($currentClasses[$found]);
                    ++$foundCount;
                }
            }
            if ($foundCount > 0) {
                $match->setAttribute($transform, $originalClasses);
                if (\count($currentClasses) > 0) {
                    $match->setAttribute('class', \join(' ', $currentClasses));
                } else {
                    $match->setAttribute('class', null);
                }
            }
        }
    }
    /**
     * Add class names.
     *
     * @param string[] $classNames
     */
    public function addClassNames($classNames)
    {
        $this->classNames = \array_merge($this->classNames, $classNames);
    }
}
