<?php

declare(strict_types=1);

namespace Aldavigdis\WpTestsStrapon;

use ZipArchive;

/**
 * FetchWP is a class for fetching WordPress archives from Github and extracting
 * them in a location that can be used by wp-tests-strapon
 */
class FetchWP
{
    public const WP_VENDOR_BASE_PATH = 'vendor/wordpress/wordpress/';

    public const WP_DEV_BASE_URL = 'https://github.com/WordPress/wordpress-develop/archive/refs/tags/';

    public const WP_DEV_TRUNK_URL = 'https://github.com/WordPress/wordpress-develop/archive/refs/heads/trunk.zip';

    public const DEFAULT_WP_DEV_BASE_VERSION = 'trunk';

    public const WP_DIST_BASE_URL = 'https://github.com/WordPress/WordPress/archive/refs/tags/';

    public const WP_DIST_URL = 'https://github.com/WordPress/WordPress/archive/refs/heads/master.zip';

    /**
     * Get a full url to a wordpress-develop zip archive on Github
     */
    public static function wpDevelopArchiveUrl(string $wp_version): string {
        return self::WP_DEV_BASE_URL . self::wpDevelopVersion($wp_version) . '.zip';
    }

    /**
     * Get the wordpress-develop version number from a "dist" version number
     *
     * Wordpress-develop uses a zero for the 3rd component of the version
     * number on Github, while the "dist" skips it.
     *
     * In short, this translates the "dist" version number such as 6.5 over to
     * 6.5.0.
     */
    public static function wpDevelopVersion(string $wp_version): string {
        $wp_version_components = explode('.', $wp_version);

        if ( sizeof($wp_version_components) === 2 ) {
            array_push($wp_version_components, '0');
        }

        return (string) implode('.', $wp_version_components);
    }

    /**
     * Get a full url to a WordPress zip archive on Github
     */
    public static function archiveUrl(
        string $wp_version = Defaults::WP_VERSION
    ): string {
        return self::WP_DIST_BASE_URL . $wp_version . '.zip';
    }

    /**
     * Get the base directory for the test environment
     *
     * This usually starts with /tmp on Linux, but MacOS and Windows use a
     * different temp directory per user that needs to be accounted for.
     */
    public static function extractDirPath(): string
    {
        return sys_get_temp_dir() . '/' . 'wp-tests-strapon/';
    }

    /**
     * Get a full path to a WordPress test environment
     */
    public static function wpInstallationPath(
        string $wp_version = Defaults::WP_VERSION,
        string $basename = 'WordPress'
    ): string {
        return self::extractDirPath() . $basename . '-' . $wp_version . '/';
    }

    /**
     * Generate a unique file path for a zip archive
     */
    public static function archiveFilePath(): string
    {
        return sys_get_temp_dir() . '/' . uniqid() . '.zip';
    }

    /**
     * Download a zip archive from Github
     *
     * @param string $wp_version The WordPress version string.
     * @param string $base_url   The base URL (sans the version string) for the archive.
     *
     * @return string|false The file path to the downloaded archive on success,
     *                      false on failure.
     */
    public static function downloadArchive(
        string $wp_version = Defaults::WP_VERSION,
        string $type = 'dist'
    ): string|false {
        switch ($type) {
            case 'develop':
                $url = self::wpDevelopArchiveUrl($wp_version);
                break;
            default:
                $url = self::archiveUrl($wp_version);
        }
        $file_path = self::archiveFilePath();
        $curl      = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result      = curl_exec($curl);
        $errno       = curl_errno($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if (($errno > 0) || ($http_status !== 200)) {
            return false;
        }

        file_put_contents($file_path, $result);

        return $file_path;
    }

    /**
     * Extract downloaded zip archive
     *
     * @param string $archive_file_path The path to the zip file
     * @param bool   $delete            Delete the zip file if true
     *
     * @return bool True on success, false on failure.
     */
    public static function extractArchive(
        string $archive_file_path,
        bool $delete = true
    ): bool {
        if (is_dir(self::extractDirPath()) === false) {
            mkdir(self::extractDirPath());
        }

        $zip = new ZipArchive();
        if ($zip->open($archive_file_path) !== true) {
            return false;
        }
        if ($zip->extractTo(self::extractDirPath()) === false) {
            return false;
        }
        $zip->close();

        if ($delete === true) {
            unlink($archive_file_path);
        }

        return true;
    }

    /**
     * Check if a test environment has been installed
     *
     * @param string $wp_version The WordPress version for the environment
     * @param string $basename   The "base name" if the environment. This is
     *                           usually WordPress for most versions, but
     *                           wordpress for the unbuilt version of WP as they
     *                           come from different repositories with different
     *                           standards.
     *
     * @return bool True if the environment is installed, false if it isn't.
     */
    public static function isInstalled(
        string $wp_version,
        string $basename = 'WordPress'
    ): bool {
        return is_dir(self::wpInstallationPath($wp_version, $basename));
    }
}
