<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

use stdClass;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Make a given option langugae-depending. That means, reading a option by a unique name
 * reads the value of the option from the language context. This also works for write.
 *
 * Note: You need to refactor your `update_option_{option}` and `option_{option}`
 * hooks to listen to all changes, perhaps with a startsWith().
 *
 * @see https://developer.wordpress.org/reference/hooks/option_option/
 * @see https://developer.wordpress.org/reference/hooks/update_option_option/
 * @internal
 */
class LanguageDependingOption
{
    /**
     * See AbstractSyncPlugin.
     *
     * @var AbstractSyncPlugin
     */
    private $comp;
    private $originalOptionName;
    private $default;
    private $defaultBehavior;
    const DEFAULT_USE_NON_EMPTY_FROM_OTHER_LANGUAGES = 1;
    /**
     * C'tor.
     *
     * @param AbstractSyncPlugin $comp
     * @param string $originalOptionName
     * @param mixed $default
     * @param int $defaultBehavior See `DEFAULT_` constants
     * @codeCoverageIgnore
     */
    public function __construct($comp, $originalOptionName, $default, $defaultBehavior = 0)
    {
        $this->comp = $comp;
        $this->originalOptionName = $originalOptionName;
        $this->default = $default;
        $this->defaultBehavior = $defaultBehavior;
        $this->hooks();
    }
    /**
     * Initialize hooks.
     */
    protected function hooks()
    {
        // Only do this for synced plugins
        if ($this->getComp() instanceof AbstractSyncPlugin) {
            \add_filter('pre_option_' . $this->getOriginalOptionName(), [$this, 'pre_option'], 10, 2);
            \add_filter('pre_update_option_' . $this->getOriginalOptionName(), [$this, 'pre_update_option'], 10, 3);
        }
    }
    /**
     * Read from original value.
     *
     * @param mixed $pre_option
     * @param string $option
     */
    public function pre_option($pre_option, $option)
    {
        $optionName = $this->getOptionName();
        if ($optionName === $option) {
            // No context, skip this behavior
            return $pre_option;
        }
        // Initially add option value with default to avoid `autoloading` issues
        \add_option($optionName, \get_option($optionName, $this->getDefault()));
        $defaultIdentifier = new stdClass();
        $value = \get_option($optionName, $defaultIdentifier);
        // Default handling
        if ($value === $defaultIdentifier || $this->getDefaultBehavior() === self::DEFAULT_USE_NON_EMPTY_FROM_OTHER_LANGUAGES && empty($value)) {
            return $this->fetchDefaultValue();
        }
        return $value;
    }
    /**
     * Fetch the default behavior.
     */
    protected function fetchDefaultValue()
    {
        switch ($this->getDefaultBehavior()) {
            case self::DEFAULT_USE_NON_EMPTY_FROM_OTHER_LANGUAGES:
                $otherLanguages = $this->getComp()->getActiveLanguages();
                $defaultIdentifier = new stdClass();
                // Higher priority for default language
                \array_unshift($otherLanguages, $this->getComp()->getDefaultLanguage());
                foreach ($otherLanguages as $otherLanguage) {
                    $optionName = $this->getOriginalOptionName() . '-' . $otherLanguage;
                    $optionValue = \get_option($optionName, $defaultIdentifier);
                    if ($optionValue !== $defaultIdentifier && !empty($optionValue)) {
                        return $optionValue;
                    }
                }
                break;
            default:
                return $this->getDefault();
        }
    }
    /**
     * Redirect the update of the context.
     *
     * @param mixed $value The new, unserialized option value.
     * @param mixed $old_value The old option value.
     * @param string $option Option name.
     */
    public function pre_update_option($value, $old_value, $option)
    {
        $optionName = $this->getOptionName();
        if ($optionName === $option) {
            // No context, skip this behavior
            return $value;
        }
        \update_option($optionName, $value);
        // We do not want to update the original value
        return $old_value;
    }
    /**
     * Get option name for this context.
     */
    protected function getOptionName()
    {
        $context = $this->getComp()->getCurrentLanguageFallback();
        return $this->getOriginalOptionName() . (empty($context) ? '' : '-' . $context);
    }
    /**
     * Get AbstractSyncPlugin.
     *
     * @codeCoverageIgnore
     */
    public function getComp()
    {
        return $this->comp;
    }
    /**
     * Get original option name.
     *
     * @codeCoverageIgnore
     */
    public function getOriginalOptionName()
    {
        return $this->originalOptionName;
    }
    /**
     * Get default value.
     *
     * @codeCoverageIgnore
     */
    public function getDefault()
    {
        return $this->default;
    }
    /**
     * Get default value behavior.
     *
     * @codeCoverageIgnore
     */
    public function getDefaultBehavior()
    {
        return $this->defaultBehavior;
    }
}
