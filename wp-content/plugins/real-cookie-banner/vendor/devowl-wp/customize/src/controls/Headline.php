<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\controls;

use WP_Customize_Control;
use WP_Customize_Manager;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Provide an additional headline in customize.
 * @internal
 */
class Headline extends WP_Customize_Control
{
    /**
     * Type.
     *
     * @var string
     */
    public $type = 'headline';
    /**
     * Used level.
     *
     * @var string
     */
    public $level = 2;
    /**
     * Sub-headline has no background color.
     *
     * @var boolean
     */
    public $isSubHeadline = \false;
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
        return \array_merge(parent::json(), ['level' => $this->level, 'className' => $this->isSubHeadline ? 'customize-control-headline-without-bg' : '', 'skipFieldSet' => \true]);
    }
}
