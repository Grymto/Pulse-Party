<?php

namespace DevOwl\RealCookieBanner\import;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\import\ImportBlocker;
use DevOwl\RealCookieBanner\import\ImportCookies;
use DevOwl\RealCookieBanner\import\ImportCustomizeBanner;
use DevOwl\RealCookieBanner\import\ImportGroups;
use DevOwl\RealCookieBanner\import\ImportMessages;
use DevOwl\RealCookieBanner\import\ImportSettings;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Allows to import a JSON string with specific options. If you want to learn more about the scheme,
 * please have a look at the output of the export.
 * @internal
 */
class Import
{
    use UtilsProvider;
    use ImportGroups;
    use ImportSettings;
    use ImportCookies;
    use ImportBlocker;
    use \DevOwl\RealCookieBanner\import\ImportBannerLinks;
    use \DevOwl\RealCookieBanner\import\ImportTcfVendorConfigurations;
    use ImportCustomizeBanner;
    use ImportMessages;
    const IMPORT_POST_STATI = ['keep', 'publish', 'private', 'draft'];
    /**
     * Default cookie group when it can not be assigned to one.
     *
     * @var int
     */
    private $cookieGroup = null;
    /**
     * Force or "keep" a given post status.
     *
     * @var string
     */
    private $cookieStatus = 'keep';
    /**
     * Skip already existing services.
     *
     * @var boolean
     */
    private $cookieSkipExisting = \true;
    /**
     * Force or "keep" a given post status.
     *
     * @var string
     */
    private $blockerStatus = 'keep';
    /**
     * Skip existing blocker.
     *
     * @var boolean
     */
    private $blockerSkipExisting = \true;
    /**
     * Force or "keep" a given post status.
     *
     * @var string
     */
    private $tcfVendorConfigurationStatus = 'keep';
    /**
     * The importing JSON as associative array.
     */
    private $json = [];
    /**
     * Messages occured while importing.
     */
    private $messages = [];
    /**
     * A map of post_name -> duplicate ID so it can be used for blocker import.
     * E. g. if a new cookie is created through import, the new ID should be used instead
     * of the defined one in `blocker`.
     */
    protected $mapCookies = [];
    /**
     * A map of post_name -> duplicate ID so it can be used for blocker import.
     * E. g. if a new TCF vendor configuration is created through import, the new ID should be used instead
     * of the defined one in `blocker`.
     */
    protected $mapTcfVendorConfigurations = [];
    /**
     * C'tor.
     *
     * @param array $json
     */
    private function __construct($json)
    {
        $this->json = $json;
    }
    /**
     * Import all available settings in the passed JSON. This also respects the correct
     * order of imports.
     */
    public function import()
    {
        $this->importSettings();
        $this->importCookieGroups();
        $this->importCookies();
        $this->importTcfVendorConfigurations();
        $this->importBlocker();
        $this->importBannerLinks();
        $this->importCustomizeBanner();
    }
    /**
     * Import settings.
     */
    public function importSettings()
    {
        $json = $this->getJSON();
        if (isset($json['settings'])) {
            if (\is_array($json['settings'])) {
                $this->doImportSettings($json['settings']);
            } else {
                $this->addMessageWrongUsageKey('settings');
            }
        }
    }
    /**
     * Import cookie groups.
     */
    public function importCookieGroups()
    {
        $json = $this->getJSON();
        if (isset($json['cookieGroups'])) {
            if (\is_array($json['cookieGroups'])) {
                $this->doImportCookieGroups($json['cookieGroups']);
            } else {
                $this->addMessageWrongUsageKey('cookieGroups');
            }
        }
    }
    /**
     * Import cookies.
     */
    public function importCookies()
    {
        $json = $this->getJSON();
        if (isset($json['cookies'])) {
            if (\is_array($json['cookies'])) {
                $this->doImportCookies($json['cookies']);
            } else {
                $this->addMessageWrongUsageKey('cookies');
            }
        }
    }
    /**
     * Import content blocker.
     */
    public function importBlocker()
    {
        $json = $this->getJSON();
        if (isset($json['blocker'])) {
            if (\is_array($json['blocker'])) {
                $this->doImportBlocker($json['blocker']);
            } else {
                $this->addMessageWrongUsageKey('blocker');
            }
        }
    }
    /**
     * Import banner links.
     */
    public function importBannerLinks()
    {
        $json = $this->getJSON();
        if (isset($json['bannerLinks'])) {
            if (\is_array($json['bannerLinks'])) {
                $this->doImportBannerLinks($json['bannerLinks']);
            } else {
                $this->addMessageWrongUsageKey('bannerLinks');
            }
        }
    }
    /**
     * Import TCF Vendor configurations.
     */
    public function importTcfVendorConfigurations()
    {
        $json = $this->getJSON();
        if (isset($json['tcfVendorConfigurations'])) {
            if (\is_array($json['tcfVendorConfigurations'])) {
                $this->doImportTcfVendorConfigurations($json['tcfVendorConfigurations']);
            } else {
                $this->addMessageWrongUsageKey('tcfVendorConfigurations');
            }
        }
    }
    /**
     * Import customize banner settings.
     */
    public function importCustomizeBanner()
    {
        $json = $this->getJSON();
        if (isset($json['customizeBanner'])) {
            if (\is_array($json['customizeBanner'])) {
                $this->doImportCustomizeBanner($json['customizeBanner']);
            } else {
                $this->addMessageWrongUsageKey('customizeBanner');
            }
        }
    }
    /**
     * Add a message to the message container.
     *
     * @param string $message
     * @param string $severity Can be: info, warning, error, success
     * @param string $fix See `import.post.tsx`
     * @param string $args See `import.post.tsx`
     */
    protected function addMessage($message, $severity, $fix = null, $args = [])
    {
        $this->getMessages()[] = \array_merge(['message' => $message, 'severity' => $severity, 'fix' => $fix], $args);
    }
    /**
     * Set default cookie group.
     *
     * @param int $value
     * @codeCoverageIgnore
     */
    public function setCookieGroup($value)
    {
        $this->cookieGroup = \intval($value);
    }
    /**
     * Set service status.
     *
     * @param string $value
     * @codeCoverageIgnore
     */
    public function setCookieStatus($value)
    {
        $this->cookieStatus = $value;
    }
    /**
     * Set cookies to skip existing.
     *
     * @param boolean $value
     * @codeCoverageIgnore
     */
    public function setCookieSkipExisting($value)
    {
        $this->cookieSkipExisting = $value;
    }
    /**
     * Set blocker status.
     *
     * @param string $value
     * @codeCoverageIgnore
     */
    public function setBlockerStatus($value)
    {
        $this->blockerStatus = $value;
    }
    /**
     * Set blocker to skip existing.
     *
     * @param boolean $value
     * @codeCoverageIgnore
     */
    public function setBlockerSkipExisting($value)
    {
        $this->blockerSkipExisting = $value;
    }
    /**
     * Set TCF Vendor configuration status.
     *
     * @param string $value
     * @codeCoverageIgnore
     */
    public function setTcfVendorConfigurationStatus($value)
    {
        $this->tcfVendorConfigurationStatus = $value;
    }
    /**
     * Get cookie group.
     *
     * @codeCoverageIgnore
     */
    public function getCookieGroup()
    {
        return $this->cookieGroup;
    }
    /**
     * Get cookie status.
     *
     * @codeCoverageIgnore
     */
    public function getCookieStatus()
    {
        return $this->cookieStatus;
    }
    /**
     * Get cookie skip existing.
     *
     * @codeCoverageIgnore
     */
    public function isCookieSkipExisting()
    {
        return $this->cookieSkipExisting;
    }
    /**
     * Get content blocker status.
     *
     * @codeCoverageIgnore
     */
    public function getBlockerStatus()
    {
        return $this->cookieStatus;
    }
    /**
     * Get blocker skip existing.
     *
     * @codeCoverageIgnore
     */
    public function isBlockerSkipExisting()
    {
        return $this->blockerSkipExisting;
    }
    /**
     * Get TCF Vendor configuration status.
     *
     * @codeCoverageIgnore
     */
    public function getTcfVendorConfigurationStatus()
    {
        return $this->tcfVendorConfigurationStatus;
    }
    /**
     * Get happened messages.
     *
     * @codeCoverageIgnore
     */
    public function &getMessages()
    {
        return $this->messages;
    }
    /**
     * Get JSON.
     *
     * @codeCoverageIgnore
     */
    public function getJSON()
    {
        return $this->json;
    }
    /**
     * Get singleton instance.
     *
     * @param array $json
     * @codeCoverageIgnore
     */
    public static function instance($json)
    {
        return new \DevOwl\RealCookieBanner\import\Import($json);
    }
}
