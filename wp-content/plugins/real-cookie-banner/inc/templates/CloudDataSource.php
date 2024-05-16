<?php

namespace DevOwl\RealCookieBanner\templates;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\datasources\AbortDataSourceDownloadException;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\datasources\AbstractDataSource;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\Utils;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Service;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Load templates from cloud API, but not every time when consumer should be invalidated. In case
 * of e.g. recalculations of middlewares, load the `before_middleware` value from database instead
 * of API fetch.
 * @internal
 */
class CloudDataSource extends AbstractDataSource
{
    use UtilsProvider;
    const PER_PAGE = 100;
    const VARIABLE_NAME_FAILED_DOWNLOAD_RETRY_IN = 'CloudDataSource.failedDownloadRetryIn';
    const RETRY_IN_SECONDS_WHEN_ASYNC_RESPONSE = 3;
    const RETRY_IN_SECONDS_WHEN_DOWNLOAD_FAILED = 60 * 5;
    const OPTION_NAME_LATEST_RESPONSE_RELEASE_INFO_PREFIX = RCB_OPT_PREFIX . '-cloud-data-source-release-info-';
    private $latestResponseMetadata;
    // Documented in AbstractDataSource
    public function retrieve()
    {
        $this->latestResponseMetadata = null;
        $shouldFetchFromApi = $this->shouldFetchFromApi();
        $result = [];
        $result = $shouldFetchFromApi ? $this->loadFromApi($result) : $this->loadFromDatabase();
        if ($shouldFetchFromApi) {
            Core::getInstance()->getNotices()->recalculatePostDependingNotices();
        }
        // The service cloud definitely provides at least one template, provide proper error handling when we already
        // have templates persisted, skip the download entirely
        if ($shouldFetchFromApi && \count($result) === 0) {
            if ($this->allowAsyncCacheCalculation()) {
                throw new AbortDataSourceDownloadException($this, ['retryIn' => self::RETRY_IN_SECONDS_WHEN_DOWNLOAD_FAILED]);
            } else {
                // When we do not have any templates, still hold the `ServiceLocalDataSource` templates (e.g. Real Cookie Banner template)
                $this->setData('retryIn', self::RETRY_IN_SECONDS_WHEN_DOWNLOAD_FAILED);
                return [];
            }
        }
        if ($this->latestResponseMetadata !== null && isset($this->latestResponseMetadata['releaseInfo'])) {
            \update_option(self::OPTION_NAME_LATEST_RESPONSE_RELEASE_INFO_PREFIX . $this->getStorageHelper()->getType(), \array_merge($this->latestResponseMetadata['releaseInfo'], ['downloadTime' => \current_time('mysql')]), \false);
        }
        return \is_array($result) ? $result : [];
    }
    /**
     * Check if this data source should read from database or external cloud API.
     */
    public function shouldFetchFromApi()
    {
        // Only load from service cloud when storage is expired, but not when using e.g. `retrieve` with `$forceInvalidate`
        $shouldFetchFromApi = $this->getConsumer()->isInvalidatedThroughStorage();
        // Check for valid license key
        if ($shouldFetchFromApi && empty($this->getLicense()->getActivation()->getCode())) {
            return \false;
        }
        return $shouldFetchFromApi;
    }
    /**
     * Load `before_middleware` from database instead of API request.
     */
    protected function loadFromDatabase()
    {
        return $this->getStorageHelper()->retrieveBy(null, null, 'before');
    }
    /**
     * Load all templates from service cloud.
     *
     * @param AbstractTemplate[] $result
     * @param string $cursor
     * @throws AbortDataSourceDownloadException
     */
    protected function loadFromApi(&$result = null, $cursor = 'id:0')
    {
        $license = $this->getLicense();
        $apiLanguage = $this->getApiLanguage();
        $allowAsync = $this->allowAsyncCacheCalculation();
        $args = ['language' => $apiLanguage, 'cursor' => $cursor, 'clientUuid' => $license->getUuid(), 'licenseKey' => $license->getActivation()->getCode(), 'limit' => self::PER_PAGE, 'minRequiredRcbVersion' => Utils::semverToInt(RCB_VERSION), 'allowAsyncCacheCalculation' => $allowAsync ? 'true' : 'false'];
        $apiUrl = Service::getExternalContainerUrl('rcb');
        $apiUrl .= \sprintf('1.0.0/template/%s', $this->getStorageHelper()->getType() === \DevOwl\RealCookieBanner\templates\StorageHelper::TYPE_BLOCKER ? 'content-blockers' : 'services');
        $apiUrl = \add_query_arg($args, $apiUrl);
        $response = \wp_remote_get($apiUrl);
        $responseCode = \wp_remote_retrieve_response_code($response);
        $responseBody = \wp_remote_retrieve_body($response);
        if (!empty($responseBody) && $responseCode === 200) {
            $responseBody = \json_decode($responseBody, ARRAY_A);
            if (isset($responseBody['data']) && \is_array($responseBody['data'])) {
                foreach ($responseBody['data'] as $row) {
                    $template = $this->getStorageHelper()->fromArray($row);
                    $template->consumerData['isCloud'] = \true;
                    $template->consumerData['isUntranslated'] = \strpos($apiLanguage, 'en') === 0 ? \false : $template->language !== $apiLanguage;
                    $result[] = $template;
                }
            }
            $responseMetadata = $responseBody['responseMetadata'] ?? null;
            if ($responseMetadata !== null) {
                $this->latestResponseMetadata = $responseMetadata;
                // Check if cache is already filled or locked, so we wait x seconds until next request
                // so we do not block WordPress usage (PHP FPM).
                $cacheInfo = $responseMetadata['cacheInfo'] ?? null;
                if ($cacheInfo !== null) {
                    $cacheStatus = \strtoupper($cacheInfo['cacheStatus'] ?? 'DISABLED');
                    if (\in_array($cacheStatus, ['PENDING', 'REQUESTED'], \true)) {
                        throw new AbortDataSourceDownloadException($this, ['retryIn' => self::RETRY_IN_SECONDS_WHEN_ASYNC_RESPONSE]);
                    }
                }
                // Load next page
                $nextCursor = $responseMetadata['nextCursor'] ?? null;
                if ($nextCursor !== null) {
                    $this->loadFromApi($result, $responseBody['responseMetadata']['nextCursor']);
                }
            }
        }
        return $result;
    }
    /**
     * Allow async cache calculation in cloud backend only when we already have templates.
     *
     * This method returns true, when at least one cloud template is already in our database.
     */
    public function allowAsyncCacheCalculation()
    {
        global $wpdb;
        $variableResolver = $this->getConsumer()->getVariableResolver();
        $context = $variableResolver->resolveRequired('context');
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\templates\StorageHelper::TABLE_NAME);
        // phpcs:disable WordPress.DB.PreparedSQL
        $cloudTemplatesCount = \intval($wpdb->get_var($wpdb->prepare("SELECT COUNT(1) FROM {$table_name} WHERE is_cloud = 1 AND context = %s", $context)));
        // phpcs:enable WordPress.DB.PreparedSQL
        return $cloudTemplatesCount > 0;
    }
    /**
     * Get the language which is requested by the cloud API.
     */
    protected function getApiLanguage()
    {
        $languageCode = $this->getConsumer()->getVariableResolver()->resolveRequired('context');
        // Cloud uses two-letter `EIso639One`
        $twoLetterLanguageCode = \substr($languageCode, 0, 2);
        // Use similar languages which are not available in service cloud
        switch ($languageCode) {
            case 'de_AT':
            case 'de_CH_informal':
                $languageCode = 'de_DE';
                break;
            case 'de_CH':
                $languageCode = 'de_DE_formal';
                break;
            default:
                break;
        }
        switch ($languageCode) {
            case 'de_DE':
                $twoLetterLanguageCode .= '_informal';
                break;
            case 'de_DE_formal':
                $twoLetterLanguageCode .= '_formal';
                break;
            default:
                break;
        }
        return $twoLetterLanguageCode;
    }
    /**
     * Getter.
     *
     * @return StorageHelper
     */
    protected function getStorageHelper()
    {
        return $this->getConsumer()->getStorage()->getHelper();
    }
    /**
     * Get current license.
     */
    protected function getLicense()
    {
        return Core::getInstance()->getRpmInitiator()->getPluginUpdater()->getCurrentBlogLicense();
    }
}
