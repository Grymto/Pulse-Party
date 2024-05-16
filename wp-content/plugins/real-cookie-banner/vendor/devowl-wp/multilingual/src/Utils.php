<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Util functionalities.
 * @internal
 */
class Utils
{
    use UtilsProvider;
    /**
     * Check if a string starts with a given needle.
     *
     * @param string $haystack The string to search in
     * @param string $needle The starting string
     * @see https://stackoverflow.com/a/834355/5506547
     * @codeCoverageIgnore
     */
    public static function startsWith($haystack, $needle)
    {
        if ($haystack === null || $needle === null) {
            return \false;
        }
        $length = \strlen($needle);
        return \substr($haystack, 0, $length) === $needle;
    }
    /**
     * Check if a string starts with a given needle.
     *
     * @param string $haystack The string to search in
     * @param string $needle The starting string
     * @see https://stackoverflow.com/a/834355/5506547
     * @codeCoverageIgnore
     */
    public static function endsWith($haystack, $needle)
    {
        if ($haystack === null || $needle === null) {
            return \false;
        }
        $length = \strlen($needle);
        if (!$length) {
            return \true;
        }
        return \substr($haystack, -$length) === $needle;
    }
    /**
     * Expand keys to dot notation so `skipKeys` works as expected and can skip
     * multidimensional arrays. This functionality also keeps the reference!
     *
     * @param mixed $arr
     * @param string $skipKeys
     * @see https://stackoverflow.com/a/40217420/5506547
     */
    public static function expandKeys(&$arr, $skipKeys = [])
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($arr), RecursiveIteratorIterator::SELF_FIRST);
        $pathMapping = [];
        $flatArray = [];
        foreach ($iterator as $key => $value) {
            $pathMapping[$iterator->getDepth()] = $key;
            if (!\is_array($value)) {
                $pathMapping = \array_slice($pathMapping, 0, $iterator->getDepth() + 1);
                // Get the value by reference
                $refValue =& $arr;
                foreach ($pathMapping as $dot) {
                    if (\in_array($dot, $skipKeys, \true)) {
                        continue 2;
                    }
                    if (\is_array($refValue)) {
                        $refValue =& $refValue[$dot];
                    } else {
                        $refValue =& $refValue->{$dot};
                    }
                }
                $flatArray[\implode('.', $pathMapping)] =& $refValue;
            }
        }
        return $flatArray;
    }
}
