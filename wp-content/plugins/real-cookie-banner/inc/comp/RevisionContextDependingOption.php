<?php

namespace DevOwl\RealCookieBanner\comp;

use DevOwl\RealCookieBanner\settings\Revision;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Make a given option context-depending. That means, reading a option by a unique name
 * reads the value of the option from the revision context. This also works for write.
 *
 * Note: You need to refactor your `update_option_{option}` and `option_{option}`
 * hooks to listen to all changes, perhaps with a startsWith().
 *
 * @see https://developer.wordpress.org/reference/hooks/option_option/
 * @see https://developer.wordpress.org/reference/hooks/update_option_option/
 * @internal
 */
class RevisionContextDependingOption
{
    private $originalOptionName;
    private $default;
    /**
     * C'tor.
     *
     * @param string $originalOptionName
     * @param mixed $default
     * @codeCoverageIgnore
     */
    public function __construct($originalOptionName, $default)
    {
        $this->originalOptionName = $originalOptionName;
        $this->default = $default;
        $this->hooks();
    }
    /**
     * Initialize hooks.
     */
    protected function hooks()
    {
        \add_filter('pre_option_' . $this->getOriginalOptionName(), [$this, 'pre_option'], 10, 2);
        \add_filter('pre_update_option_' . $this->getOriginalOptionName(), [$this, 'pre_update_option'], 10, 3);
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
        $original = $this->getOriginalOptionName();
        if ($optionName === $option) {
            // No context, skip this behavior
            return $pre_option;
        }
        // Initially add option value with default to avoid `autoloading` issues
        \add_option($optionName, \get_option($optionName, $this->getDefault()));
        $value = \get_option($optionName, $this->getDefault());
        return \apply_filters('option_' . $original, $value, $original);
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
        $context = Revision::getInstance()->getContextVariablesString();
        return $this->getOriginalOptionName() . (empty($context) ? '' : '-' . $context);
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
}
