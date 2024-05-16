<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use Exception;
/**
 * When thrown, the complete processing of the passed match is aborted and the passed match
 * is rendered to the HTML. At the end, the HTML processor is run on the HTML again.
 * @internal
 */
class RerunOnMatchException extends Exception
{
    protected static $ID = 0;
    const ID_ATTRIBUTE_NAME = 'consent-rerun-on-match-exception-id';
    private $id = 0;
    private $match;
    private $afterProcessing;
    /**
     * C'tor.
     *
     * @param AbstractMatch $match
     * @param callable $afterProcessing Allows you to register a callback which is executed on the match again.
     */
    public function __construct($match, $afterProcessing = null)
    {
        parent::__construct('');
        $this->id = ++self::$ID;
        $match->setInvisibleAttribute(self::ID_ATTRIBUTE_NAME, $this->id);
        $this->match = $match;
        $this->afterProcessing = $afterProcessing;
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
    public function getMatch()
    {
        return $this->match;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getAfterProcessing()
    {
        return $this->afterProcessing;
    }
}
