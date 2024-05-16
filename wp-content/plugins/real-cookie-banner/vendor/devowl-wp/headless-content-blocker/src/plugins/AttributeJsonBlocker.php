<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\AbstractMatcher;
/**
 * Block content deeply in JSON attributes instead of the whole element e.g. `<div class="et_pb_code_inner" data-et-multi-view
 * ="{&quot;schema&quot;:{&quot;content&quot;:{&quot;desktop&quot;:&quot;&lt;div align=&#039;center&#039;&gt;\n&lt;iframe
 * src=\&quot;https:\/\/www.google.com\/maps\/embed...`.
 *
 * Please ensure that the attribute is registered as blockable attribute e.g. by `addTagAttributeMap` or by rule.
 * @internal
 */
class AttributeJsonBlocker extends AbstractPlugin
{
    /**
     * The attributes which should be deeply crawled.
     *
     * @var string[]
     */
    private $attributes = [];
    /**
     * Called after a match got found and the matcher decided, if it should be blocked or not.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     * @return BlockedResult
     */
    public function checkResult($result, $matcher, $match)
    {
        if ($result->isBlocked()) {
            foreach ($this->attributes as $attribute) {
                $value = $match->getAttribute($attribute);
                if (Utils::isJson($value)) {
                    $newValue = $this->getHeadlessContentBlocker()->modifyAny($value);
                    $match->setAttribute($attribute, $newValue);
                    $result->disableBlocking();
                }
            }
        }
        return $result;
    }
    /**
     * Add attribute which should be considered as "is-json".
     *
     * @param string[] $attributes
     */
    public function addAttributes($attributes)
    {
        $this->attributes = \array_merge($this->attributes, $attributes);
    }
}
