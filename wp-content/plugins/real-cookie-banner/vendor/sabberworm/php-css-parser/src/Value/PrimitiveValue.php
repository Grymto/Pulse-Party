<?php

namespace DevOwl\RealCookieBanner\Vendor\Sabberworm\CSS\Value;

/** @internal */
abstract class PrimitiveValue extends Value
{
    /**
     * @param int $iLineNo
     */
    public function __construct($iLineNo = 0)
    {
        parent::__construct($iLineNo);
    }
}
