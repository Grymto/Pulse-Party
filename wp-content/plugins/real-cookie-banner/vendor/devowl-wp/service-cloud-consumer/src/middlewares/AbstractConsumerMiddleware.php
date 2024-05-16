<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
use Exception;
/**
 * Abstract implementation of a middleware for consumer life cycles.
 * @internal
 */
abstract class AbstractConsumerMiddleware extends AbstractMiddleware
{
    /**
     * Before downloading from specified datasources. This allows you to e.g. change the
     * language of the context to make translations work as expected.
     *
     * @return void
     */
    public abstract function beforeDownloadAndPersistFromDataSource();
    /**
     * Teardown of `beforeDownloadAndPersistFromDataSource`. This is also invoked when
     * the database throws the `AbortDataSourceDownloadException` exception.
     *
     * @param Exception $exception `null` when persist worked as expected, otherwise an `Exception`
     * @return void
     */
    public abstract function afterDownloadAndPersistFromDataSource($exception);
    /**
     * The download from a data source failed with an exception.
     *
     * @param Exception $exception
     * @return void
     */
    public function failedDownloadAndPersistFromDataSource($exception)
    {
        // Silence is golden.
    }
    /**
     * Before using a template and running all the middlewares of `AbstractTemplateMiddleware`.
     *
     * @param AbstractTemplate $template
     * @return void
     */
    public abstract function beforeUseTemplate($template);
    /**
     * Teardown of `beforeDownloadAndPersistFromDataSource`.
     *
     * @param AbstractTemplate $template
     * @return void
     */
    public abstract function afterUseTemplate($template);
}
