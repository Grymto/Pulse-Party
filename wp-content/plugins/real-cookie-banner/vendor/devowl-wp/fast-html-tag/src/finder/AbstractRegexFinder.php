<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils;
/**
 * Find by regular expression.
 * @internal
 */
abstract class AbstractRegexFinder extends AbstractFinder
{
    /**
     * Get regular expression.
     *
     * @param string $html
     */
    public abstract function getRegularExpression();
    /**
     * A regexp match got found. Let's create a `AbstractMatch` instance.
     *
     * @param array $m
     * @return AbstractMatch|false Returns `false` if no match got found and we cannot process it
     */
    protected abstract function createMatch($m);
    /**
     * See `AbstractFinder`.
     *
     * @param string $html
     */
    public function replace($html)
    {
        return Utils::preg_replace_callback_recursive($this->getRegularExpression(), function ($m) {
            $match = $this->createMatch($m);
            $this->applyCallbacks($match);
            if ($match === \false) {
                return $m[0];
            } elseif (!$match->isOmitted()) {
                return $match->render();
            } else {
                return '';
            }
        }, $html);
    }
}
