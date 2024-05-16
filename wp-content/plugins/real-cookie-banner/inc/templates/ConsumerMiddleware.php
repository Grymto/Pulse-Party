<?php

namespace DevOwl\RealCookieBanner\templates;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual\TemporaryTextDomain;
use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\datasources\AbortDataSourceDownloadException;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractConsumerMiddleware;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Consumer middleware for both service and blocker templates.
 * @internal
 */
class ConsumerMiddleware extends AbstractConsumerMiddleware
{
    /**
     * Temporary text domain.
     *
     * @var TemporaryTextDomain
     */
    private $td;
    // Documented in AbstractConsumerMiddleware
    public function beforeDownloadAndPersistFromDataSource()
    {
        if ($this->td !== null) {
            $this->td->teardown();
            $this->td = null;
        }
        $this->td = Hooks::getInstance()->createTemporaryTextDomain($this->getVariableResolver()->resolveRequired('context'));
        $this->setRetryIn(null);
    }
    // Documented in AbstractConsumerMiddleware
    public function afterDownloadAndPersistFromDataSource($exception)
    {
        if ($this->td !== null) {
            $this->td->teardown();
            $this->td = null;
        }
        foreach ($this->getConsumer()->getDataSources() as $ds) {
            $retryIn = $ds->getData('retryIn');
            if (\is_numeric($retryIn)) {
                $this->setRetryIn($retryIn);
            }
        }
    }
    // Documented in AbstractConsumerMiddleware
    public function failedDownloadAndPersistFromDataSource($exception)
    {
        if ($exception instanceof AbortDataSourceDownloadException) {
            $retryIn = $exception->getData('retryIn');
            if (\is_numeric($retryIn)) {
                $this->setRetryIn($retryIn);
            }
        }
    }
    // Documented in AbstractConsumerMiddleware
    public function beforeUseTemplate($template)
    {
        // Do literally the same
        $this->beforeDownloadAndPersistFromDataSource();
    }
    // Documented in AbstractConsumerMiddleware
    public function afterUseTemplate($template)
    {
        // Do literally the same
        $this->afterDownloadAndPersistFromDataSource(null);
    }
    /**
     * Set the retry-in variable in all available consumers within the consumer pools.
     *
     * @param mixed $value
     */
    protected function setRetryIn($value)
    {
        foreach ($this->getConsumer()->getPools() as $pool) {
            foreach ($pool->getConsumers() as $consumer) {
                $consumer->getVariableResolver()->add(\DevOwl\RealCookieBanner\templates\CloudDataSource::VARIABLE_NAME_FAILED_DOWNLOAD_RETRY_IN, $value);
                if ($value > 0) {
                    /**
                     * StorageHelper.
                     *
                     * @var BlockerStorage|ServiceStorage
                     */
                    $storage = $consumer->getStorage();
                    $helper = $storage->getHelper();
                    $helper->getExpireOption()->set($helper->getCacheInvalidateKey(), $value);
                }
            }
        }
    }
}
