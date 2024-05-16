<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\datasources;

use Exception;
/**
 * Retrieving data from a data source currently only allows to return an array of `AbstractTemplate`.
 * But what happens, if the download fails, especially within a pool? For this, you can throw this exception
 * and the cloud consumer automatically falls back to the latest available storage items. No items will be
 * persisted, but all data sources within the pool will be tried to download.
 * @internal
 */
class AbortDataSourceDownloadException extends Exception
{
    private $dataSource;
    private $data;
    /**
     * C'tor.
     *
     * @param Exception $previous
     * @param AbstractDataSource $dataSource
     * @param array $data Custom data which can be reused by `getData` in middlewares
     */
    public function __construct($dataSource, $data = null, $previous = null)
    {
        parent::__construct(\sprintf('The download of datasource %s was abort. Fallback to latest storage items.', \get_class($dataSource)), 0, $previous);
        $this->dataSource = $dataSource;
        $this->data = \is_array($data) ? $data : [];
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getDataSource()
    {
        return $this->dataSource;
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
