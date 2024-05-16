<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\PluginReceiver;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Constants;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Localization;
use MO;
use PO;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Allows to set a given text domain to be translated from a .mo file.
 * @internal
 */
class TemporaryTextDomain
{
    /**
     * Mo-file to MO instances for caching purposes to avoid a lot of `import_from_file` calls as
     * they are not very performant.
     *
     * @var (MO|PO)[]
     */
    private static $pomoInstances = [];
    /**
     * List of currently active temporary text domains per domain.
     *
     * @param TemporaryTextDomain[]
     */
    private static $currentlyActiveLocale = [];
    /**
     * Previous active temporary text domain.
     *
     * @var TemporaryTextDomain
     */
    private $previousActiveLocale;
    private $domain;
    private $fallbackDomain;
    private $languageFile;
    private $locale;
    /**
     * PO/MO instance. Can be null if the given language file is not found.
     *
     * @var MO|PO
     */
    private $pomo = null;
    private $skipFallbackTranslation;
    /**
     * C'tor.
     *
     * @param string $domain
     * @param string $fallbackDomain
     * @param string|string[] $languageFile Can be a `.po`, `.pot` or `.mo` file. When passed as array,
     *                                      the translation entries are merged from right to left.
     * @param string $locale
     * @param boolean $skipFallbackTranslation
     * @codeCoverageIgnore
     */
    public function __construct($domain, $fallbackDomain, $languageFile, $locale, $skipFallbackTranslation = \false)
    {
        $this->domain = $domain;
        $this->fallbackDomain = $fallbackDomain;
        $this->languageFile = $languageFile;
        $this->locale = $locale;
        $this->skipFallbackTranslation = $skipFallbackTranslation;
        $this->fromLanguageFiles($this->languageFile);
        $this->hooks();
    }
    /**
     * Create PO/MO instance from language file(s).
     *
     * @param string|string[] $languageFile Can be a `.po`, `.pot` or `.mo` file. When passed as array,
     *                                      the translation entries are merged from right to left.
     */
    protected function fromLanguageFiles($languageFile)
    {
        if (\is_string($languageFile)) {
            $pomo = $this->createPomo($this->languageFile);
            if ($pomo !== \false) {
                $this->pomo = $pomo;
            }
        } elseif (\is_array($languageFile)) {
            foreach ($languageFile as $l) {
                $pomo = $this->createPomo($l);
                if ($pomo === \false) {
                    continue;
                } elseif ($this->pomo === null) {
                    // No PO/MO instance yet
                    $this->pomo = $pomo;
                } else {
                    // Merge to our POMO file
                    foreach ($pomo->entries as $key => $value) {
                        if (!isset($this->pomo->entries[$key])) {
                            $this->pomo->entries[$key] = $value;
                        }
                    }
                }
            }
        }
    }
    /**
     * Create a PO or MO instance with memory caching enabled.
     *
     * @param string $languageFile
     * @see https://stackoverflow.com/a/28604283/5506547
     */
    protected function createPomo($languageFile)
    {
        if (!\file_exists($languageFile)) {
            return \false;
        }
        $cachedMo = self::$pomoInstances[$languageFile] ?? null;
        if ($cachedMo) {
            return $cachedMo;
        } else {
            if (Utils::endsWith($languageFile, '.pot')) {
                require_once ABSPATH . 'wp-includes/pomo/po.php';
                $pomo = new PO();
            } else {
                $pomo = new MO();
            }
            $pomo->import_from_file($languageFile);
            self::$pomoInstances[$languageFile] = $pomo;
            return $pomo;
        }
    }
    /**
     * Create `gettext` hooks.
     */
    protected function hooks()
    {
        $this->previousActiveLocale = self::$currentlyActiveLocale[$this->domain] ?? null;
        self::$currentlyActiveLocale[$this->domain] = $this;
        \add_filter('gettext', [$this, 'gettext'], 1, 3);
        \add_filter('gettext_with_context', [$this, 'gettext_with_context'], 1, 4);
    }
    /**
     * Teardown the `gettext` filter.
     */
    public function teardown()
    {
        self::$currentlyActiveLocale[$this->domain] = $this->previousActiveLocale;
        \remove_filter('gettext', [$this, 'gettext'], 1, 3);
        \remove_filter('gettext_with_context', [$this, 'gettext_with_context'], 1, 4);
    }
    /**
     * Checs if this temporary text domain is the latest registered one in stack?
     */
    public function isCurrentlyActive()
    {
        $active = self::$currentlyActiveLocale[$this->domain] ?? null;
        return $active === $this;
    }
    /**
     * Gettext filter.
     *
     * @param string $translation Translated text.
     * @param string $text Text to translate.
     * @param string $domain Text domain. Unique identifier for retrieving translated strings.
     */
    public function gettext($translation, $text, $domain)
    {
        if ($this->isCurrentlyActive() && $this->domain === $domain && $translation === $text) {
            if ($this->pomo === null) {
                if ($this->skipFallbackTranslation) {
                    return $text;
                }
                return \call_user_func('translate', $text, $this->fallbackDomain);
            }
            return $this->pomo->translate($text);
        }
        return $translation;
    }
    /**
     * Gettext with context filter.
     *
     * @param string $translation Translated text.
     * @param string $text Text to translate.
     * @param string $context Text context.
     * @param string $domain Text domain. Unique identifier for retrieving translated strings.
     */
    public function gettext_with_context($translation, $text, $context, $domain)
    {
        if ($this->isCurrentlyActive() && $this->domain === $domain && $translation === $text) {
            if ($this->pomo === null) {
                if ($this->skipFallbackTranslation) {
                    return $text;
                }
                return \call_user_func('translate_with_gettext_context', $text, $context, $this->fallbackDomain);
            }
            return $this->pomo->translate($text, $context);
        }
        return $translation;
    }
    /**
     * Get all translation entries of the given MO file.
     */
    public function getEntries()
    {
        return isset($this->pomo) ? $this->pomo->entries : [];
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getLocale()
    {
        return $this->locale;
    }
    /**
     * Create a temporary text domain from a given WP React Starter plugin receiver.
     *
     * @param string $domain
     * @param string $fallbackDomain
     * @param PluginReceiver $receiver
     * @param AbstractSyncPlugin|string $compLanguageOrLocale
     * @param string $overrideClass A class with a `getPotLanguages` method
     */
    public static function fromPluginReceiver($domain, $fallbackDomain, $receiver, $compLanguageOrLocale, $overrideClass = null)
    {
        $skipFallbackTranslation = \false;
        // Never use the language of the compatible plugin while deactivation
        if (isset($_GET['action'], $_GET['plugin']) && $_GET['action'] === 'deactivate') {
            $useLocale = '';
        } elseif (\is_string($compLanguageOrLocale)) {
            $useLocale = $compLanguageOrLocale;
        } else {
            $useLocale = $compLanguageOrLocale->getWordPressCompatibleLanguageCode($compLanguageOrLocale->getCurrentLanguageFallback());
        }
        // Fallback to blog language
        if (empty($useLocale)) {
            // Do not use `get_bloginfo('language')` or `determine_locale` as they also respect
            // the configured user language. But at this point we definitely need the blog language.
            $useLocale = \str_replace('-', '_', \get_locale());
        }
        $path = \untrailingslashit(\plugin_dir_path($receiver->getPluginConstant(Constants::PLUGIN_CONST_FILE))) . $receiver->getPluginData('DomainPath');
        $mo = \trailingslashit($path) . $receiver->getPluginConstant(Constants::PLUGIN_CONST_TEXT_DOMAIN) . '-' . $useLocale . '.mo';
        if ($overrideClass !== null) {
            /**
             * Localization instance.
             *
             * @var Localization
             */
            $overrideClassInstance = new $overrideClass();
            // Check if fallback should be skipped if the POT language is currently in use
            $skipFallbackTranslation = \in_array($useLocale, $overrideClassInstance->getPotLanguages(), \true);
            list(, $newMofile) = $overrideClassInstance->getMofilePath($mo, $fallbackDomain);
            if ($newMofile !== \false) {
                $mo = $newMofile;
            }
        }
        return new TemporaryTextDomain($domain, $fallbackDomain, $mo, $useLocale, $skipFallbackTranslation);
    }
}
