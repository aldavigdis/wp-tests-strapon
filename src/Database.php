<?php

declare(strict_types=1);

namespace Aldavigdis\WpTestsStrapon;

use mysqli;
use Throwable;

/**
 * The Database class is used for database connectivity; namely testing,
 * creating and dropping the database
 */
class Database {
    /**
     * Test the database connection
     *
     * Attempts to connect to the MySQL server without selecting the database.
     */
    static public function testConnection(
        string $hostname = DB_HOST,
        string $username = DB_USER,
        string $password = DB_PASSWORD,
        string $database = DB_NAME
    ): bool {
        try {
            $connection = mysqli_connect(
                hostname: $hostname,
                username: $username,
                password: $password,
                database: $database
            );
        } catch (Throwable) {
            return false;
        }

        if ($connection !== false) {
            return true;
        }

        return false;
    }

    /**
     * Check if the database exists
     */
    static public function exsists(
        string $hostname = DB_HOST,
        string $username = DB_USER,
        string $password = DB_PASSWORD,
        string $database = DB_NAME
    ): bool {
        $mysqli = new mysqli($hostname, $username, $password, null);
        try {
            $result = $mysqli->query('USE `' . $database . '`');
        } catch (Throwable) {
            return false;
        }

        if ($result !== false) {
            return true;
        }

        return false;
    }

    /**
     * Drop the database
     */
    static public function drop(
        string $hostname = DB_HOST,
        string $username = DB_USER,
        string $password = DB_PASSWORD,
        string $database = DB_NAME
    ): bool {
        $mysqli = new mysqli($hostname, $username, $password, null);

        return (bool) $mysqli->query(
            'DROP DATABASE IF EXISTS `' . $database . '`'
        );
    }

    /**
     * Create the database
     */
    static public function create(
        string $hostname = DB_HOST,
        string $username = DB_USER,
        string $password = DB_PASSWORD,
        string $database = DB_NAME
    ): bool {
        $mysqli = new mysqli($hostname, $username, $password, null);

        return (bool) $mysqli->query(
            'CREATE DATABASE IF NOT EXISTS `' . $database . '`'
        );
    }
}