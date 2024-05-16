<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker;

/**
 * Statically hold all found markups (e.g. inline styles) in a single object instance to be memory-low
 * instead of saving the markup string in each blocked result.
 * @internal
 */
class Markup
{
    /**
     * Pool.
     *
     * @var Markup[]
     */
    private static $pool = [];
    private $id;
    private $content;
    /**
     * C'tor.
     *
     * @param string $id
     * @param string $content
     * @codeCoverageIgnore
     */
    private function __construct($id, $content)
    {
        $this->id = $id;
        $this->content = \trim($content);
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getContent()
    {
        return $this->content;
    }
    /**
     * `toString`. Allow grouping with e.g. `array_unique`.
     */
    public function __toString()
    {
        return $this->id;
    }
    /**
     * Persist a markup in pool or return the existing one.
     *
     * @param string $markup
     */
    public static function persist($markup)
    {
        if (empty($markup)) {
            return null;
        }
        $id = \md5($markup);
        $found = self::get($id);
        if (!$found) {
            $found = new Markup($id, $markup);
            self::$pool[$id] = $found;
        }
        return $found;
    }
    /**
     * Get the markup instance for a given ID (the MD5 hash of the markup).
     *
     * @param string $id
     */
    public static function get($id)
    {
        return self::$pool[$id] ?? null;
    }
}
