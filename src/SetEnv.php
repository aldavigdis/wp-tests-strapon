<?php

declare(strict_types=1);

namespace Aldavigdis\WpTestsStrapon;

use Aldavigdis\WpTestsStrapon\Config;

/**
 * The SetEnv class initiates environment variables and global constants and
 * helps us with going around limitations set by PHPUnit 9
 */
class SetEnv {
    /**
     * Supress PHPUnit framework errors if the current version of PHPUnit is
     * version 10 or newer.
     *
     * This is because WordPress itself is stuck with version 9 of PHPUnit and
     * thus assumes certain classes to exsist. If we don't fake them like this,
     * the WP test suite will throw an error.
     */
    public static function supress(): void {
        if ( 1 === version_compare( \PHPUnit\Runner\Version::id(), '10' ) ) {
            require __DIR__ . '/../supressors/SupressFramework.php';
            require __DIR__ . '/../supressors/SupressFrameworkError.php';
        }
    }

    /**
     * Set the WP_VERSION environment variable if it is not set already
     */
    public static function setWpVersion(): void {
        if (getenv('WP_VERSION') === false) {
           putenv('WP_VERSION=master');
        }
    }

    /**
     * Set the global WP_TESTS_CONFIG_FILE_PATH constant if not set already
     */
    public static function setConfigFilePath(): void {
        if (defined('WP_TESTS_CONFIG_FILE_PATH') === false) {
            define(
                'WP_TESTS_CONFIG_FILE_PATH',
                Config::path()
             );
        }
    }
}