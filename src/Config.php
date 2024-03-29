<?php

declare(strict_types=1);

namespace Aldavigdis\WpTestsStrapon;

use Aldavigdis\WpTestsStrapon\Defaults;
use Aldavigdis\WpTestsStrapon\FetchWP;
use Throwable;

/**
 * The Config class creates a WordPress test configuration file
 *
 * This is essentially similar as wp-config.php but specific to testing in
 * PHPUnit. The default values are set in the Defaults class and are overwritten
 * using environment variables of the same name.
 *
 * @see Aldavigdis\WpTestsStrapon\Defaults
 */
class Config
{
    public string $config_contents;
    public string $wp_version;

    public string $db_name;
    public string $db_user;
    public string $db_password;
    public string $db_host;

    /**
     * The Config constructor
     *
     * Sets the values of the test config file to be generated. The defaults for
     * each are overwritten using environment variables of the same name.
     */
    public function __construct(
        string $wp_version = Defaults::WP_VERSION,
        string $wp_default_theme = Defaults::WP_DEFAULT_THEME,
        bool $wp_tests_mutlisite = Defaults::WP_TESTS_MULTISITE,
        bool $wp_debug = Defaults::WP_DEBUG,
        string $table_prefix = Defaults::TABLE_PREFIX,
        string $db_name = Defaults::DB_NAME,
        string $db_user = Defaults::DB_USER,
        string $db_password = Defaults::DB_PASSWORD,
        string $db_host = Defaults::DB_HOST,
        string $wp_tests_domain = Defaults::WP_TESTS_DOMAIN,
        string $wp_tests_email = Defaults::WP_TESTS_EMAIL,
        string $wp_tests_title = Defaults::WP_TESTS_TITLE,
        string $wp_php_binary = Defaults::WP_PHP_BINARY,
        string $wplang = ''
    ) {
        $this->wp_version = getenv('WP_VERSION') ?: $wp_version;

        $this->db_name     = getenv('DB_NAME') ?: $db_name;
        $this->db_user     = getenv('DB_USER') ?: $db_user;
        $this->db_password = getenv('DB_PASSWORD') ?: $db_password;
        $this->db_host     = getenv('DB_HOST') ?: $db_host;

        $this->config_contents = '<?php' . "\n\n";

        $this->config_contents .= $this->formatComment(
            "WordPress test environment configuration file\n\n" .
            "This the configuration file for your WordPress testing environment.\n" .
            "It was automatically generated by the `wp-tests-strapon` package.\n\n" .
            "Feel free to edit it and to commit it to your codebase."
        ) . "\n";

        $this->config_contents .= $this->formatConstant(
            'ABSPATH',
            FetchWP::wpInstallationPath($wp_version),
            'Path to the WordPress codebase you\'d like to test against'
        );

        $this->config_contents .= $this->formatConstant(
            'WP_DEFAULT_THEME',
            getenv('WP_DEFAULT_THEME') ?: $wp_default_theme,
            "Path to the theme to test with"
        );

        $this->config_contents .= $this->formatConstant(
            'WP_TESTS_MULTISITE',
            $wp_tests_mutlisite,
            'Test with multisite enabled'
        );

        $this->config_contents .= $this->formatConstant(
            'WP_DEBUG',
            boolval(getenv('WP_DEBUG')) ?: $wp_debug,
            'Test with WordPress debug mode'
        );

        $this->config_contents .= $this->formatVariable(
            'table_prefix',
            boolval(getenv('TABLE_PREFIX')) ?: $table_prefix,
            null,
            true
        );

        $this->config_contents .= $this->formatConstant(
            'DB_NAME',
            getenv('DB_NAME') ?: $db_name,
            "The database to use for the test\n\n" .
            "Note that all the relevant tables with the above prefix will\n" .
            "be dropped the test suite has run, so don't use the same\n" .
            "database and prefix as your development or production environment."
        );

        $this->config_contents .= $this->formatConstant(
            'DB_USER',
            getenv('DB_USER') ?: $db_user
        );

        $this->config_contents .= $this->formatConstant(
            'DB_PASSWORD',
            getenv('DB_PASSWORD') ?: $db_password
        );

        $this->config_contents .= $this->formatConstant(
            'DB_HOST',
            getenv('DB_HOST') ?: $db_host
        );

        $this->config_contents .= $this->formatConstant(
            'WP_TESTS_DOMAIN',
            getenv('WP_TESTS_DOMAIN') ?: $wp_tests_domain
        );

        $this->config_contents .= $this->formatConstant(
            'WP_TESTS_EMAIL',
            getenv('WP_TESTS_DOMAIN') ?: $wp_tests_email
        );

        $this->config_contents .= $this->formatConstant(
            'WP_TESTS_TITLE',
            getenv('WP_TESTS_TITLE') ?: $wp_tests_title
        );

        $this->config_contents .= $this->formatConstant(
            'WP_PHP_BINARY',
            getenv('WP_PHP_BINARY') ?: $wp_php_binary
        );

        $this->config_contents .= $this->formatConstant(
            'WPLANG',
            getenv('WPLANG') ?: $wplang
        );
    }

    /**
     * Get the path of the test configuration file
     *
     * On Linux, the file may usually end up in /tmp/wp-tests-strapon/ but for
     * Windows and MacOS, each user has their own temporary directory.
     */
    public static function path(): string
    {
        if (defined('WP_TESTS_CONFIG_FILE_PATH') === true) {
            return constant('WP_TESTS_CONFIG_FILE_PATH');
        }

        return sys_get_temp_dir() . "/wp-tests-strapon/config.php";
    }

    /**
     * Save the test configuration file
     *
     * @return bool True on success, false on failure.
     */
    public function save(): bool
    {
        if (is_dir(dirname(self::path())) === false) {
            mkdir(dirname(self::path()));
        }

        $file = fopen(self::path(), 'w');
        if (fwrite($file, rtrim($this->config_contents) . PHP_EOL)) {
            return true;
        }

        return false;
    }

    /**
     * Delete the config file
     */
    public function drop(): bool
    {
        return unlink(self::path());
    }

    /**
     * Format a comment block for the configuration file
     */
    private function formatComment(string $comment): string
    {
        $comment_lines = explode(PHP_EOL, $comment);
        $entity = '';
        if (is_string($comment)) {
            $entity .= '/**' . PHP_EOL;
            foreach ($comment_lines as $c) {
                $trimmed_comment = trim($c);
                $entity .= ' *';
                if (empty($trimmed_comment) === false) {
                    $entity .= ' ' . $trimmed_comment;
                }
                $entity .= PHP_EOL;
            }
            $entity .= ' **/' . PHP_EOL;
        }
        return $entity;
    }

    /**
     * Clean and strip a variable or constant name
     */
    private function cleanName(string $name): string
    {
        return trim(addslashes(str_replace([' ', '\''], '_', $name)));
    }

    /**
     * Clean and strip a variable or contant value
     */
    private function cleanValue(string|bool $value): string
    {
        if (is_bool($value) === true) {
            if ($value === true) {
                return 'true';
            }
            return 'false';
        }

        return '\'' . trim(addslashes($value)) . '\'';
    }

    /**
     * Format a PHP constant declaration in the config file
     */
    private function formatConstant(
        string $name,
        bool|string $value,
        ?string $comment = null
    ): string {
        $clean_name  = $this->cleanName($name);
        $clean_value = $this->cleanValue($value);

        $entity = '';
        if (is_string($comment) === true) {
            $entity = $this->formatComment($comment);
        }
        $entity .= "define('$clean_name', $clean_value);" . PHP_EOL . PHP_EOL;

        return $entity;
    }

    /**
     * Format a variable decleation in the config file
     */
    private function formatVariable(
        string $name,
        string $value,
        ?string $comment = null
    ): string {
        $clean_name  = $this->cleanName($name);
        $clean_value = $this->cleanValue($value);

        $entity = '';
        if (is_string($comment) === true) {
            $entity = $this->formatComment($comment);
        }
        $entity .= '$' . "$clean_name = $clean_value;" . PHP_EOL . PHP_EOL;

        return $entity;
    }
}
