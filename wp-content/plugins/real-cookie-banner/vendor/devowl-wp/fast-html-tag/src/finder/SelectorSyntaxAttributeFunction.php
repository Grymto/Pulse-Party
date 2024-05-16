<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils;
/**
 * An function definition for `SelectorSyntaxAttribute` with function name and parsed arguments.
 * @internal
 */
class SelectorSyntaxAttributeFunction
{
    private $attribute;
    private $name;
    private $arguments;
    /**
     * C'tor.
     *
     * @param SelectorSyntaxAttribute $attribute
     * @param string $name
     * @param string[] $arguments
     * @codeCoverageIgnore
     */
    public function __construct($attribute, $name, $arguments)
    {
        $this->attribute = $attribute;
        $this->name = $name;
        $this->arguments = $arguments;
    }
    /**
     * Execute the function with registered functions.
     *
     * @param SelectorSyntaxMatch $match
     */
    public function execute($match)
    {
        $functionCallback = $this->getFinder()->getFastHtmlTag()->getSelectorSyntaxFunction($this->name);
        if (\is_callable($functionCallback)) {
            return $functionCallback($this, $match, $match->getAttribute($this->getAttributeName()));
        }
        return \true;
    }
    /**
     * Get argument by name.
     *
     * @param string $argument
     * @param mixed $default
     */
    public function getArgument($argument, $default = null)
    {
        return $this->arguments[$argument] ?? $default;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getAttribute()
    {
        return $this->attribute;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getAttributeName()
    {
        return $this->attribute->getAttribute();
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getArguments()
    {
        return $this->arguments;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getFinder()
    {
        return $this->getAttribute()->getFinder();
    }
    /**
     * Convert a string expression to multiple function instances.
     *
     * Example: `matchUrls(arg1=test),another()`;
     *
     * @param SelectorSyntaxAttribute $attribute
     * @param string $expression
     * @return SelectorSyntaxAttributeFunction[]
     */
    public static function fromExpression($attribute, $expression)
    {
        $result = [];
        if (\is_string($expression)) {
            $splitExpression = \explode('),', $expression);
            foreach ($splitExpression as $expr) {
                $functionName = \explode('(', $expr, 2);
                if (isset($functionName[0])) {
                    $arguments = \preg_replace('/\\)$/m', '', $functionName[1] ?? '');
                    $functionName = \trim($functionName[0]);
                    $argsArray = [];
                    if (!empty($arguments)) {
                        $argsArray = Utils::isJson($arguments);
                        if ($argsArray === \false) {
                            \parse_str($arguments, $parseStr);
                            $argsArray = $parseStr;
                        }
                    }
                    $result[] = new SelectorSyntaxAttributeFunction($attribute, $functionName, $argsArray);
                }
            }
        }
        return $result;
    }
}
