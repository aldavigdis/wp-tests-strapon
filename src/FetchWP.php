<?php

declare(strict_types=1);

namespace Aldavigdis\WpTestsStrapon;

use ZipArchive;

class FetchWP
{
    public const WP_VENDOR_BASE_PATH = 'vendor/wordpress/wordpress/';

    public const WP_DEV_BASE_URL = 'https://github.com/WordPress/wordpress-develop/archive/refs/heads/';

    public const DEFAULT_WP_DEV_BASE_VERSION = 'trunk';

    public const WP_DIST_BASE_URL = 'https://github.com/WordPress/WordPress/archive/refs/tags/';

    public const WP_DIST_URL = 'https://github.com/WordPress/WordPress/archive/refs/heads/master.zip';

    public const DEFAULT_WP_VERSION = 'master';

    public static function archiveUrl(
        string $wp_version = self::DEFAULT_WP_VERSION,
        string $base_url = self::WP_DIST_BASE_URL
    ): string {
        if ($wp_version === 'master') {
            return self::WP_DIST_URL;
        }

        return $base_url . $wp_version . '.zip';
    }

    public static function extractDirPath(): string
    {
        return sys_get_temp_dir() . '/' . 'wp-tests-strapon/';
    }

    public static function wpInstallationPath(
        string $wp_version = 'master',
        string $basename = 'WordPress'
    ): string {
        return self::extractDirPath() . $basename . '-' . $wp_version . '/';
    }

    public static function archiveFilePath(): string
    {
        return sys_get_temp_dir() . '/' . uniqid() . '.zip';
    }

    public static function downloadArchive(
        string $wp_version = 'master',
        string $base_url = self::WP_DIST_BASE_URL
    ): string|false {
        $file_path = self::archiveFilePath();
        $curl      = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::archiveUrl($wp_version, $base_url));
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

    public static function extractArchive(
        string $archive_file_path,
        bool $delete = true
    ): bool {
        if (is_dir(self::extractDirPath()) === false) {
            mkdir(self::extractDirPath());
        }

        $zip = new ZipArchive();
        if ($zip->open($archive_file_path) !== true) {
            echo "Hmm\n";
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

    public static function isInstalled(
        string $wp_version,
        string $basename = 'WordPress'
    ): bool {
        return is_dir(self::wpInstallationPath($wp_version, $basename));
    }
}
