<?php

namespace DevOwl\RealCookieBanner\Vendor\Sabberworm\CSS;

/** @internal */
interface Renderable
{
    /**
     * @return string
     */
    public function __toString();
    /**
     * @return string
     */
    public function render(OutputFormat $oOutputFormat);
    /**
     * @return int
     */
    public function getLineNo();
}
