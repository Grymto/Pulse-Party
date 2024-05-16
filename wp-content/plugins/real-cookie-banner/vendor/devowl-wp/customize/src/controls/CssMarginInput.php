<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls;

use WP_Customize_Control;
use WP_Customize_Manager;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Provide a CSS margin input with 4 dimension input fields (top, right, bottom, left).
 * It can also be used for padding.
 * @internal
 */
class CssMarginInput extends WP_Customize_Control
{
    /**
     * Type.
     *
     * @var string
     */
    public $type = 'cssMargin';
    /**
     * Used dashicon.
     *
     * @var string
     */
    public $dashicon = 'editor-expand';
    /**
     * C'tor.
     *
     * @param WP_Customize_Manager $manager
     * @param string $id
     * @param array $args
     */
    public function __construct($manager, $id, $args = [])
    {
        parent::__construct($manager, $id, $args);
    }
    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     */
    public function json()
    {
        return \array_merge(parent::json(), ['dashicon' => $this->dashicon]);
    }
    /**
     * Render empty control (no `input`!) cause it is rendered via frontend. Avoids also
     * `value="Array"` to be printed.
     *
     * @see https://git.io/JcFCR
     */
    protected function render_content()
    {
        $input_id = '_customize-input-' . $this->id;
        $description_id = '_customize-description-' . $this->id;
        if (!empty($this->label)) {
            \printf('<label for="%s" class="customize-control-title">%s</label>', \esc_attr($input_id), \esc_html($this->label));
        }
        if (!empty($this->description)) {
            \printf('<span id="%s" class="description customize-control-description">%s</span>', \esc_attr($description_id), $this->description);
        }
    }
}
