<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue;

// Simply check for defined constants, we do not need to `die` here
if (\defined('ABSPATH')) {
    Core::setupConstants();
    Localization::instanceThis()->hooks();
}
