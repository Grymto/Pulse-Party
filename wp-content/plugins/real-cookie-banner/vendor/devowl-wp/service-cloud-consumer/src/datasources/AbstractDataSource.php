<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\datasources;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer\ServiceCloudConsumer;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
/**
 * Abstract implementation of a data source which allows to retrieve `AbstractTemplate` instances
 * from remote. This should actually not implement any caching mechanism as this is done through
 * `AbstractStore`!
 * @internal
 */
abstract class AbstractDataSource
{
    private $consumer;
    private $data;
    /**
     * C'tor.
     *
     * @param ServiceCloudConsumer $consumer
     */
    public function __construct($consumer)
    {
        $this->consumer = $consumer;
        $this->data = [];
    }
    /**
     * Retrieve all templates from data source (e.g. request to service cloud).
     *
     * @return AbstractTemplate[]
     */
    public abstract function retrieve();
    /**
     * Clear all available data.
     *
     * @codeCoverageIgnore
     */
    public function clearData()
    {
        $this->data = [];
    }
    /**
     * Set data which could be reused by middlewares.
     *
     * @param string $key
     * @param mixed $value
     * @codeCoverageIgnore
     */
    public function setData($key, $value)
    {
        $this->data[$key] = $value;
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
     * @param string $key
     * @codeCoverageIgnore
     */
    public function getData($key)
    {
        return $this->data[$key] ?? null;
    }
}
