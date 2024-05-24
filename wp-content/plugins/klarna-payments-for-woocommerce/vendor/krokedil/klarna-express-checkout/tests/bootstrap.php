<?php
// Include the composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Initialize WP_Mock
WP_Mock::setUsePatchwork( true );
WP_Mock::bootstrap();

// Include the mocks
require_once __DIR__ . '/mocks.php';
