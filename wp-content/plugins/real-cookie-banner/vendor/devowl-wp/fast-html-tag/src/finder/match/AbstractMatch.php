<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\FastHtmlTag;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\AbstractFinder;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\SelectorSyntaxFinder;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils;
/**
 * Abstract match defining a `AbstractFinder` match.
 * @internal
 */
abstract class AbstractMatch
{
    const HTML_ATTRIBUTE_INVISIBLE_PREFIX = 'consent-attribute-invisible-';
    private $finder;
    private $originalMatch;
    private $tag;
    private $attributes;
    /**
     * Key => value.
     */
    private $deletedAttributes = [];
    /**
     * New attribute name => Old attribute name.
     */
    private $renamedAttributes = [];
    /**
     * Attributes, which are represented as data URL.
     *
     * @var string[]
     */
    private $dataUrlAttributes = [];
    private $changed = \false;
    private $beforeTag = '';
    private $afterTag = '';
    private $doOmit = \false;
    private $data = [];
    /**
     * C'tor.
     *
     * @param AbstractFinder $finder
     * @param string $originalMatch
     * @param string $tag
     * @param array $attributes
     */
    public function __construct($finder, $originalMatch, $tag, $attributes)
    {
        $this->finder = $finder;
        $this->originalMatch = $originalMatch;
        $this->tag = \strtolower($tag);
        $this->attributes = $attributes ?? [];
        $this->transformBase64DataUrls();
    }
    /**
     * Automatically transform base64-encoded data URLs and memorize in `$this->dataUrlAttributes` for rendering process.
     */
    protected function transformBase64DataUrls()
    {
        foreach ($this->attributes as $key => $value) {
            $isDataUrl = Utils::isBase64DataUrl($value);
            if ($isDataUrl) {
                $this->dataUrlAttributes[$key] = $isDataUrl[0];
                // Do not use `setAttribute` as this should not lead to `setChanged(true)`
                $this->attributes[$key] = $isDataUrl[1];
                //$this->setAttribute($key, $isDataUrl[1]);
            }
        }
    }
    /**
     * Render this match to valid HTML. Please pass your result to `encloseRendered`
     * and return that result.
     *
     * @return string
     */
    public abstract function render();
    /**
     * Render attributes to a single string so it can be e.g. used with `<div %s`.
     */
    protected function renderAttributes()
    {
        $attributes = $this->getAttributes();
        if (\count($attributes) === 0) {
            return '';
        }
        foreach ($attributes as $key => $val) {
            $isAttributeDataUrl = $this->isAttributeDataUrl($key);
            if ($isAttributeDataUrl !== \false) {
                $attributes[$key] = \sprintf('data:%s;base64,%s', $this->dataUrlAttributes[$key], \base64_encode($val));
            }
        }
        return ' ' . Utils::htmlAttributes($attributes);
    }
    /**
     * Use `beforeTag` and `afterTag`.
     *
     * @param string $html
     */
    protected function encloseRendered($html)
    {
        return \sprintf('%s%s%s', $this->getBeforeTag(), $html, $this->getAfterTag());
    }
    /**
     * Allows to check if this match also matches a given selector syntax (or multiple when passing array).
     *
     * @param string|string[] $expression
     */
    public function matches($expression)
    {
        $useExpressions = \is_array($expression) ? $expression : [$expression];
        $fastHtmlTag = new FastHtmlTag();
        $matchesCount = 0;
        foreach ($useExpressions as $useExpression) {
            $selectorSyntaxFinder = SelectorSyntaxFinder::fromExpression($useExpression);
            if ($selectorSyntaxFinder !== \false) {
                $selectorSyntaxFinder->addCallback(function () use(&$matchesCount) {
                    $matchesCount++;
                });
                $fastHtmlTag->addFinder($selectorSyntaxFinder);
            }
        }
        $fastHtmlTag->modifyHtml($this->render());
        return $matchesCount === \count($useExpressions);
    }
    /**
     * Omit the rendering.
     */
    public function omit()
    {
        $this->doOmit = \true;
    }
    /**
     * Should we omit the rendering for this node?
     */
    public function isOmitted()
    {
        return $this->doOmit;
    }
    /**
     * Check if a given attribute is a data URL and return the mime type.
     *
     * @param string $key
     */
    public function isAttributeDataUrl($key)
    {
        return $this->dataUrlAttributes[$key] ?? \false;
    }
    /**
     * Check if the original match is a self-closing HTML tag. E.g. `<link />` vs. `<link></link>`.
     */
    public function isSelfClosing()
    {
        return \boolval(\preg_match('/\\/\\s*>\\s*$/', $this->getOriginalMatch()));
    }
    /**
     * Get attribute by key.
     *
     * @param string $key
     * @param mixed $default
     * @param boolean $allowRenamed
     */
    public function getAttribute($key, $default = null, $allowRenamed = \false)
    {
        $value = $this->attributes[$key] ?? null;
        if ($value === null && $allowRenamed) {
            $newAttributeName = \array_search($key, $this->getRenamedAttributes(), \true);
            if ($newAttributeName !== \false) {
                return $this->getAttribute($newAttributeName, $default);
            }
        }
        return $value === null ? $default : $value;
    }
    /**
     * Check if the match has a given attribute.
     *
     * @param string $key
     */
    public function hasAttribute($key)
    {
        return isset($this->attributes[$key]);
    }
    /**
     * Check if the match has a given invisible attribute.
     *
     * @param string $key
     */
    public function hasInvisibleAttribute($key)
    {
        $key = \sprintf('%s%s', self::HTML_ATTRIBUTE_INVISIBLE_PREFIX, $key);
        return isset($this->attributes[$key]);
    }
    /**
     * Calculate a unique key for this match.
     *
     * @param string[] $idKeys Consider ID keys as unique (ordered by priority)
     * @param string[][] $looseAttributes
     * @return string|null Can return `null` if we cannot calculate a unique key for this container
     */
    public function calculateUniqueKey($idKeys = ['id'], $looseAttributes = [])
    {
        $result = [];
        // Generate unique key by ID attribute
        foreach ($idKeys as $idKey) {
            if ($this->hasAttribute($idKey)) {
                $result[$idKey] = \trim($this->getAttribute($idKey));
                break;
            }
        }
        if (\count($result) === 0) {
            // Fallback to loose identification (all attributes)
            $looseAttributes['attributes'] = $this->getAttributes();
            if (\count($looseAttributes['attributes']) !== 0) {
                \ksort($looseAttributes['attributes']);
                $result = $looseAttributes;
            }
        }
        return \count($result) > 0 ? \md5($this->getTag() . '.' . \json_encode($result)) : null;
    }
    /**
     * Allows to set an "invisible" attribute to the match. Due to the fact, that when a change got detected
     * after modifying the HTML, the replacer is run again, you will loose data set through e.g. `setData` or `setBeforeTag`.
     * With invisible attributes you can get that attribute data in the next iteration, but it will never print out in the browser.
     *
     * @param string $key
     * @param mixed|null $value
     * @return string
     */
    public function setInvisibleAttribute($key, $value)
    {
        // Use base64-encoded value so the removal of such attributes is much faster with preg_replace instead of
        // traversing the DOM again.
        $this->setAttribute(\sprintf('%s%s', self::HTML_ATTRIBUTE_INVISIBLE_PREFIX, $key), $value === null ? null : \base64_encode(\json_encode(['val' => $value])));
    }
    /**
     * Get invisible attribute by key.
     *
     * @param string $key
     * @param mixed $default
     * @param boolean $allowRenamed
     */
    public function getInvisibleAttribute($key, $default = null)
    {
        $result = $this->getAttribute(\sprintf('%s%s', self::HTML_ATTRIBUTE_INVISIBLE_PREFIX, $key), null);
        return $result === null ? $default : \json_decode(\base64_decode($result), ARRAY_A)['val'];
    }
    /**
     * Set attribute by key and value.
     *
     * @param string $key
     * @param mixed|null $value
     * @param null|false|string $dataUrlTransformation Pass a mime type so it gets transformed to base64-encoded attribute value
     */
    public function setAttribute($key, $value, $dataUrlTransformation = null)
    {
        $this->setChanged(\true);
        $attributes =& $this->attributes;
        $dataUrlAttributes =& $this->dataUrlAttributes;
        if ($value === null) {
            if (isset($attributes[$key])) {
                $this->deletedAttributes[$key] = $this->attributes[$key];
                unset($attributes[$key]);
            }
            if (isset($this->renamedAttributes[$key])) {
                unset($this->renamedAttributes[$key]);
            }
            if ($dataUrlTransformation === \false && isset($dataUrlAttributes[$key])) {
                unset($dataUrlAttributes[$key]);
            }
        } else {
            // Only track renamed attributes with string value (e.g. booleans should never be considered as "renamed")
            if (\is_string($value)) {
                $attributeWithSameValue = \array_search($value, $this->deletedAttributes, \true);
                if ($attributeWithSameValue !== \false) {
                    $this->renamedAttributes[$key] = $attributeWithSameValue;
                }
            }
            $attributes[$key] = $value;
            if (\is_string($dataUrlTransformation)) {
                $dataUrlAttributes[$key] = $dataUrlTransformation;
            }
        }
    }
    /**
     * Setter.
     *
     * @param array $attributes
     */
    public function setAttributes($attributes)
    {
        // Do not override attributes array directly to keep track of deleted and renamed attributes
        // This procedure deletes existing attributes
        $currentAttributes = \array_keys($this->attributes);
        foreach ($currentAttributes as $key) {
            if (!isset($attributes[$key])) {
                $this->setAttribute($key, null);
            }
        }
        // This procedure updates existing attributes
        foreach ($currentAttributes as $key) {
            if (isset($attributes[$key])) {
                $this->setAttribute($key, $attributes[$key]);
            }
        }
        // This procedure adds new attributes
        foreach (\array_keys($attributes) as $key) {
            if (!\in_array($key, $currentAttributes, \true)) {
                $this->setAttribute($key, $attributes[$key]);
            }
        }
    }
    /**
     * Setter. Use this with caution! Due to the fact, this can break your HTML code,
     * if the finder does not hold the end tag!
     *
     * @param string $string
     */
    public function setTag($string)
    {
        $this->setChanged(\true);
        $this->tag = $string;
    }
    /**
     * Setter.
     *
     * Attention: If setting this, make sure, your match does no longer match again so you
     * do not end up in an endless loop.
     *
     * @param string $string
     * @codeCoverageIgnore
     */
    public function setBeforeTag($string)
    {
        $this->beforeTag = $string;
    }
    /**
     * Setter.
     *
     * Attention: If setting this, make sure, your match does no longer match again so you
     * do not end up in an endless loop.
     *
     * Attention #2: Do not use this if you can use `setBeforeTag` as it can be unsafe to use `setAfterTag`,
     * e.g. for `<iframe>AFTER_TAG_CURSOR</iframe>`.
     *
     * @param string $string
     * @codeCoverageIgnore
     */
    public function setAfterTag($string)
    {
        $this->afterTag = $string;
    }
    /**
     * Indicate that the match has a change. You need to mark this to `true` to apply changes to your HTML.
     *
     * @param boolean $status
     */
    public function setChanged($status)
    {
        $this->changed = $status;
    }
    /**
     * Allows to set additional data for this match. This date is never present in the HTML output, it
     * should only be used for internal processing (e.g. in plugins)!
     *
     * If you want to make data available to multiple modification-processes you can use invisible attributes.
     *
     * @param string $key
     * @param mixed $data
     */
    public function setData($key, $data)
    {
        $this->data[$key] = $data;
    }
    /**
     * Get additional blocking data.
     *
     * @param string $key
     */
    public function getData($key)
    {
        return $this->data[$key] ?? null;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getFinder()
    {
        return $this->finder;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getOriginalMatch()
    {
        return $this->originalMatch;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getTag()
    {
        return $this->tag;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function hasChanged()
    {
        return $this->changed;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getBeforeTag()
    {
        return $this->beforeTag;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getAfterTag()
    {
        return $this->afterTag;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getDeletedAttributes()
    {
        $result = [];
        foreach ($this->deletedAttributes as $deletedKey => $deletedValue) {
            if (\array_search($deletedKey, $this->renamedAttributes, \true) === \false) {
                $result[$deletedKey] = $deletedValue;
            }
        }
        return $result;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getRenamedAttributes()
    {
        return $this->renamedAttributes;
    }
}
