<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\storages;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer\ServiceCloudConsumer;
/**
 * Abstract implementation of a storage which saves and retrieves instances of `AbstractTemplate[]`.
 * It acts like a cache of data sources.
 * @internal
 */
abstract class AbstractStorage
{
    private $consumer;
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
     * Retrieve all templates from storage (e.g. request from filesystem, redis or database).
     *
     * Return `false` if data needs to be retrieved from data sources again.
     *
     * @param boolean|string $forceInvalidate See `ServiceCloudConsumer#retrieveBy` parameters
     * @return AbstractTemplate[]|false Return `false` if the storage does not know about any templates
     *              so data sources are used to retrieve data. Afterwards, this is called again.
     */
    public abstract function retrieve($forceInvalidate);
    /**
     * Get a single template instance by criteria.
     *
     * Predefined `$field` and `$value` schemas which you should receive to make it work with all our middlewares:
     *
     * ```
     * ResolveServiceTemplatesMiddleware
     * $field   = `identifier`
     * $value   = string
     *
     * VersionsMiddleware
     * $field   = `versions`
     * $value   = [string (identifier), ...number]
     * ```
     *
     * @param string $field Can be `identifier`, or whatever you want to implement to receive templates
     * @param mixed $value
     * @param boolean|string $forceInvalidate See `ServiceCloudConsumer#retrieveBy` parameters
     * @return AbstractTemplate[]|false Return `false` if the storage does
     *              not know about any templates so data sources are used to retrieve data. Afterwards,
     *              this is called again.
     */
    public abstract function retrieveBy($field, $value, $forceInvalidate);
    /**
     * Persist all templates to storage (e.g. save to filesystem, redis or database).
     *
     * @param AbstractTemplate[] $templates
     * @return void
     */
    public abstract function persist($templates);
    /**
     * Check if the storage should be invalidated (e.g. after 24 hours).
     *
     * @return boolean
     */
    public abstract function shouldInvalidate();
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getConsumer()
    {
        return $this->consumer;
    }
}
