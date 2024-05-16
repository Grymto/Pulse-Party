<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\datasources\AbortDataSourceDownloadException;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\datasources\AbstractDataSource;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middleware\AbstractMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractConsumerMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractPoolMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractTemplateMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\storages\AbstractStorage;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\storages\InMemoryStorage;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
/**
 * Service cloud consumer implementation which can be used for service or
 * blocker templates.
 * @internal
 */
class ServiceCloudConsumer
{
    private $typeClass;
    /**
     * In-memory cache of collected templates of all data sources.
     *
     * @var AbstractTemplate[]
     */
    private $templates;
    /**
     * Data sources.
     *
     * @var AbstractDataSource[]
     */
    private $dataSources = [];
    /**
     * Middlewares.
     *
     * @var AbstractMiddleware[]
     */
    private $middlewares = [];
    /**
     * Storage.
     *
     * @var AbstractStorage
     */
    private $storage;
    /**
     * VariableResolver.
     *
     * @var VariableResolver
     */
    private $variableResolver;
    /**
     * Consumer pools.
     *
     * @var ConsumerPool[]
     */
    private $pools = [];
    /**
     * See getter.
     */
    private $invalidatedThroughStorage = \false;
    private $invalidatedThroughStorageCurrentTransaction = \false;
    private $retrievingDataTransactionCount = 0;
    /**
     * C'tor.
     *
     * @param string $typeClass Should be the class which extends from `AbstractTemplate`
     */
    public function __construct($typeClass)
    {
        $this->typeClass = $typeClass;
        $this->storage = new InMemoryStorage($this);
        $this->variableResolver = new VariableResolver($this);
    }
    /**
     * Get all template instances.
     *
     * @param boolean|string $forceInvalidate Pass `"never"` to skip download from data source completely
     * @return AbstractTemplate[]
     */
    public function retrieve($forceInvalidate = \false)
    {
        ++$this->retrievingDataTransactionCount;
        try {
            /**
             * Only modify this variable when we are in the first `retrieve` transaction, to make
             * `$this->isInvalidatedThroughStorage()` also work for recursive calls.
             */
            $modifyInvalidatedThroughStorage = $this->retrievingDataTransactionCount === 1;
            if ($modifyInvalidatedThroughStorage) {
                $this->invalidatedThroughStorage = \false;
            }
            if ($forceInvalidate !== 'never' && ($forceInvalidate || ($this->invalidatedThroughStorageCurrentTransaction = $this->storage->shouldInvalidate()))) {
                if ($modifyInvalidatedThroughStorage) {
                    $this->invalidatedThroughStorage = $this->invalidatedThroughStorageCurrentTransaction;
                }
                // Force usage from data source
                if (!$this->downloadAndPersistFromDataSource()) {
                    return $this->retrieve('never');
                }
            } elseif (!\is_array($this->templates)) {
                // No results known yet, get from storage
                $storageResult = $this->storage->retrieve($forceInvalidate);
                if ($storageResult === \false) {
                    // The storage does not know anything about templates yet, so usage from data source
                    if ($forceInvalidate !== 'never') {
                        $this->downloadAndPersistFromDataSource();
                    }
                } else {
                    $this->templates = $this->filterTemplates($storageResult);
                }
            }
            if (\is_array($this->templates)) {
                foreach ($this->templates as $template) {
                    $template->retrieved();
                }
            }
            if ($modifyInvalidatedThroughStorage) {
                $this->invalidatedThroughStorage = \false;
            }
            return \is_array($this->templates) ? $this->templates : [];
        } finally {
            --$this->retrievingDataTransactionCount;
        }
    }
    /**
     * Get a single template instance by criteria.
     *
     * @param string $field Can be `identifier` or your custom implementation which is supported by your storage
     * @param mixed $value
     * @param boolean|string $forceInvalidate Pass `"never"` to skip download from data source completely
     * @return AbstractTemplate[]
     */
    public function retrieveBy($field, $value, $forceInvalidate = \false)
    {
        ++$this->retrievingDataTransactionCount;
        try {
            $result = [];
            /**
             * Only modify this variable when we are in the first `retrieve` transaction, to make
             * `$this->isInvalidatedThroughStorage()` also work for recursive calls.
             */
            $modifyInvalidatedThroughStorage = $this->retrievingDataTransactionCount === 1;
            if ($modifyInvalidatedThroughStorage) {
                $this->invalidatedThroughStorage = \false;
            }
            if ($forceInvalidate !== 'never' && ($forceInvalidate || ($this->invalidatedThroughStorageCurrentTransaction = $this->storage->shouldInvalidate()))) {
                if ($modifyInvalidatedThroughStorage) {
                    $this->invalidatedThroughStorage = $this->invalidatedThroughStorageCurrentTransaction;
                }
                // Force usage from data source
                if (!$this->downloadAndPersistFromDataSource()) {
                    return $this->retrieveBy($field, $value, 'never');
                }
                $storageResult = $this->storage->retrieveBy($field, $value, $forceInvalidate);
                $result = $storageResult === \false ? [] : $this->filterTemplates($storageResult);
            } else {
                $storageResult = $this->storage->retrieveBy($field, $value, $forceInvalidate);
                if ($storageResult === \false) {
                    // The storage does not know anything about templates yet, so usage from data source
                    if ($forceInvalidate !== 'never') {
                        $this->downloadAndPersistFromDataSource();
                        $storageResult = $this->storage->retrieveBy($field, $value, $forceInvalidate);
                        $result = $storageResult === \false ? [] : $this->filterTemplates($storageResult);
                    }
                } else {
                    $result = $this->filterTemplates($storageResult);
                }
            }
            foreach ($result as $template) {
                $template->retrieved();
            }
            if ($modifyInvalidatedThroughStorage) {
                $this->invalidatedThroughStorage = \false;
            }
            return $result;
        } finally {
            --$this->retrievingDataTransactionCount;
        }
    }
    /**
     * Add data source to our consumer.
     *
     * @param AbstractDataSource $dataSource
     */
    public function addDataSource($dataSource)
    {
        $this->dataSources[] = $dataSource;
    }
    /**
     * Add middleware to our consumer.
     *
     * @param AbstractMiddleware $middleware
     */
    public function addMiddleware($middleware)
    {
        $this->middlewares[] = $middleware;
    }
    /**
     * Add new consumer pool.
     *
     * @param ConsumerPool $pool
     * @codeCoverageIgnore
     */
    public function addPool($pool)
    {
        $this->pools[] = $pool;
    }
    /**
     * Remove unwanted classes from the templates array.
     *
     * @param AbstractTemplate[] $arr
     * @return AbstractTemplate[]
     */
    protected function filterTemplates($arr)
    {
        if (!\is_array($arr)) {
            return [];
        }
        foreach ($arr as $key => $template) {
            if (\get_class($template) !== $this->typeClass) {
                unset($arr[$key]);
            }
        }
        return \array_values($arr);
    }
    /**
     * Download templates from data sources and persist to storage. This also
     * respected the `ConsumerPool` if one given.
     *
     * @throws AbortDataSourceDownloadException
     */
    protected function downloadAndPersistFromDataSource()
    {
        $this->runMiddleware(AbstractConsumerMiddleware::class, function ($middleware) {
            $middleware->beforeDownloadAndPersistFromDataSource();
        });
        $runFailedMiddleware = null;
        try {
            if (\count($this->pools) === 0) {
                $this->storage->persist($this->downloadFromDataSource());
            } else {
                /**
                 * Get unique consumers from multiple pools.
                 *
                 * @var ServiceCloudConsumer[]
                 */
                $consumers = [];
                foreach ($this->pools as $pool) {
                    foreach ($pool->getConsumers() as $c) {
                        if (!\in_array($c, $consumers, \true)) {
                            $consumers[] = $c;
                        }
                    }
                }
                $typeClassToAllTemplates = [];
                // All datasources from all pools should try to download, and if one fails with
                // `AbortDataSourceDownloadException`, no template should be persisted.
                $abortDataSourceDownloadException = null;
                foreach ($consumers as $consumer) {
                    try {
                        $typeClassToAllTemplates[$consumer->getTypeClass()] = $consumer->downloadFromDataSource();
                    } catch (AbortDataSourceDownloadException $e) {
                        $abortDataSourceDownloadException = $e;
                    }
                }
                if ($abortDataSourceDownloadException !== null) {
                    throw $abortDataSourceDownloadException;
                }
                // Apply middlewares (make sure the middleware is only called once)
                $appliedPoolMiddleware = [];
                foreach ($consumers as $consumer) {
                    $consumer->runMiddleware(AbstractPoolMiddleware::class, function ($middleware) use(&$typeClassToAllTemplates, &$appliedPoolMiddleware) {
                        $clazzName = \get_class($middleware);
                        if (\in_array($clazzName, $appliedPoolMiddleware, \true)) {
                            return;
                        }
                        $appliedPoolMiddleware[] = $clazzName;
                        foreach ($typeClassToAllTemplates as $dataSourceResult) {
                            foreach ($dataSourceResult as $dsTemplate) {
                                $middleware->beforePersistTemplateWithinPool($dsTemplate, $typeClassToAllTemplates);
                            }
                        }
                    });
                }
                // Finally, store it
                foreach ($consumers as $consumer) {
                    $consumer->getStorage()->persist($typeClassToAllTemplates[$consumer->getTypeClass()]);
                }
                $consumer->runMiddleware(AbstractPoolMiddleware::class, function ($middleware) use($consumers, &$typeClassToAllTemplates) {
                    $middleware->afterPersistTemplatesWithinPool($consumers, $typeClassToAllTemplates);
                });
            }
            return \true;
        } catch (AbortDataSourceDownloadException $e) {
            $runFailedMiddleware = $e;
            return \false;
        } finally {
            $this->runMiddleware(AbstractConsumerMiddleware::class, function ($middleware) use($runFailedMiddleware) {
                $middleware->afterDownloadAndPersistFromDataSource($runFailedMiddleware);
            });
            if ($runFailedMiddleware !== null) {
                $this->runMiddleware(AbstractConsumerMiddleware::class, function ($middleware) use($runFailedMiddleware) {
                    $middleware->failedDownloadAndPersistFromDataSource($runFailedMiddleware);
                });
            }
        }
    }
    /**
     * Download templates from data sources. This does not persist anything to the storage,
     * for this use `downloadAndPersistFromDataSource` or `retrieve` instead!
     *
     * @throws AbortDataSourceDownloadException
     * @return AbstractTemplate[]
     */
    protected function downloadFromDataSource()
    {
        /**
         * Data source results, merged.
         *
         * @var AbstractTemplate[]
         */
        $dataSourceResult = [];
        // All datasources from this consumer should try to download, and if one fails with
        // `AbortDataSourceDownloadException`, no template should be persisted.
        $abortDataSourceDownloadException = null;
        foreach ($this->dataSources as $dataSource) {
            $dataSource->clearData();
            try {
                foreach ($dataSource->retrieve() as $dsTemplate) {
                    if (isset($dataSourceResult[$dsTemplate->identifier])) {
                        // Already added, skip this one
                        continue;
                    }
                    $dataSourceResult[$dsTemplate->identifier] = $dsTemplate;
                }
            } catch (AbortDataSourceDownloadException $e) {
                $abortDataSourceDownloadException = $e;
            }
        }
        if ($abortDataSourceDownloadException !== null) {
            throw $abortDataSourceDownloadException;
        }
        foreach ($dataSourceResult as $dsTemplate) {
            $dsTemplate->memoizeBeforeMiddleware();
        }
        // Apply middlewares
        $this->runMiddleware(AbstractTemplateMiddleware::class, function ($middleware) use($dataSourceResult) {
            foreach ($dataSourceResult as $dsTemplate) {
                $middleware->beforePersistTemplate($dsTemplate, $dataSourceResult);
            }
        });
        $dataSourceResult = $this->filterTemplates(\array_values($dataSourceResult));
        // Sort by headline
        \usort($dataSourceResult, function ($a, $b) {
            return \strcmp($a->headline, $b->headline);
        });
        $this->templates = $dataSourceResult;
        return $dataSourceResult;
    }
    /**
     * Run a closure for each middleware type. This also respects `AbstractConsumerMiddleware`.
     *
     * @param string $middlewareTypeClass
     * @param callable $closure
     */
    public function runMiddleware($middlewareTypeClass, $closure)
    {
        foreach ($this->middlewares as $middleware) {
            if (\is_a($middleware, $middlewareTypeClass) && !$middleware->isSuspended()) {
                $closure($middleware);
            }
        }
    }
    /**
     * Setter.
     *
     * @param AbstractStorage $storage
     * @codeCoverageIgnore
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
    }
    /**
     * Setter.
     *
     * @param VariableResolver $resolver
     * @codeCoverageIgnore
     */
    public function setVariableResolver($resolver)
    {
        $this->variableResolver = $resolver;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getTypeClass()
    {
        return $this->typeClass;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getStorage()
    {
        return $this->storage;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getPools()
    {
        return $this->pools;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getDataSources()
    {
        return $this->dataSources;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getVariableResolver()
    {
        return $this->variableResolver;
    }
    /**
     * Checks if the current `retrieve()`/`retrieveBy()` statement got invalidated through the storage.
     * Use this within your middlewares so you can e.g. determine to fetch from an external source or not.
     *
     * @param boolean $includePools If you run within a pool, it checks the other service cloud consumers within the pool, too
     */
    public function isInvalidatedThroughStorage($includePools = \true)
    {
        if ($this->invalidatedThroughStorage) {
            return \true;
        }
        if ($includePools) {
            foreach ($this->getPools() as $pool) {
                foreach ($pool->getConsumers() as $consumer) {
                    if ($consumer->isInvalidatedThroughStorage(\false)) {
                        return \true;
                    }
                }
            }
        }
        return \false;
    }
}
