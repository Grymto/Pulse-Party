<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer\ServiceCloudConsumer;
/**
 * Abstract implementation of a middleware for templates.
 * @internal
 */
abstract class AbstractMiddleware
{
    private $consumer;
    private $suspended = \false;
    /**
     * C'tor.
     *
     * @param ServiceCloudConsumer $consumer
     */
    public function __construct($consumer)
    {
        $this->consumer = $consumer;
    }
    /**
     * Allows to suspend or resume the middleware to take effect. This is efficient
     * if a middleware could be called recursively.
     *
     * @param boolean $state
     */
    public function suspend($state)
    {
        $old = $this->suspended;
        $this->suspended = $state;
        return $old;
    }
    /**
     * Check if the middleware is suspended.
     */
    public function isSuspended()
    {
        return $this->suspended;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getConsumer()
    {
        return $this->consumer;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getVariableResolver()
    {
        return $this->getConsumer()->getVariableResolver();
    }
}
