<?php

require dirname(__FILE__) . '/vendor/autoload.php';

if (getenv('WP_VERSION') === false) {
    putenv('WP_VERSION=master');
}

if (defined('WP_TESTS_CONFIG_FILE_PATH') === false) {
    define(
        'WP_TESTS_CONFIG_FILE_PATH',
        Aldavigdis\WpTestsStrapon\Config::path()
    );
}

Aldavigdis\WpTestsStrapon\Bootstrap::init(getenv('WP_VERSION'));

require getcwd() . '/vendor/wordpress/wordpress/tests/phpunit/includes/functions.php';
require getcwd() . '/vendor/wordpress/wordpress/tests/phpunit/includes/bootstrap.php';
