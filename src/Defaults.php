<?php

declare(strict_types=1);

namespace Aldavigdis\WpTestsStrapon;

/**
 * The Defaults class contains the default values for the test configuration file
 */
class Defaults
{
    public const WP_VERSION = 'master';
    public const WP_DEFAULT_THEME = 'default';
    public const WP_TESTS_MULTISITE = false;
    public const WP_DEBUG = true;
    public const TABLE_PREFIX = 'wptest_';
    public const DB_NAME = 'wordpress-test';
    public const DB_USER = 'root';
    public const DB_PASSWORD = 'password';
    public const DB_HOST = '127.0.0.1';
    public const WP_TESTS_DOMAIN = 'example.org';
    public const WP_TESTS_EMAIL = 'admin@example.org';
    public const WP_TESTS_TITLE = 'Test Site';
    public const WP_PHP_BINARY = 'php';
    public const WPLANG = '';
}
