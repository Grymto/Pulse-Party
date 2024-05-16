<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls;

use WP_Customize_Control;
use WP_Customize_Manager;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Provide a TinyMCE in customizer.
 *
 * @see https://aurooba.com/adding-tinymce-editor-in-wordpress-customizer/
 * @internal
 */
class TinyMCE extends WP_Customize_Control
{
    /**
     * Allow media buttons in a TinyMCE editor.
     */
    public $mediaButtons = \false;
    public $toolbar1 = 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,spellchecker,fullscreen';
    public $toolbar2 = 'strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help';
    /**
     * Enqueue our scripts and styles
     */
    public function enqueue()
    {
        \wp_enqueue_editor();
    }
    /**
     * Pass our TinyMCE toolbar config to JavaScript
     */
    public function to_json()
    {
        parent::to_json();
        $this->json['mediaButtons'] = $this->mediaButtons;
        $this->json['toolbar1'] = $this->toolbar1;
        $this->json['toolbar2'] = $this->toolbar2;
    }
    /**
     * Render TinyMCE editor.
     */
    protected function render_content()
    {
        $value = $this->value();
        echo '<div class="devowl-tinymce-control">';
        \printf('<label for="_customize-input-%s" class="customize-control-title customize-text_editor">%s</label>', \esc_attr($this->id), \esc_html($this->label));
        if (!empty($this->description)) {
            \printf('<span id="_customize-description-%s" class="description customize-control-description">%s</span>', \esc_attr($this->id), $this->description);
        }
        \printf('<textarea id="%s-link" class="customize-control-devowl-tinymce-editor" type="hidden" ', $this->id);
        $this->link();
        \printf('>%s</textarea>', \esc_html($value));
        echo '</div>';
    }
}
