<?php

declare(strict_types=1);

namespace Aldavigdis\WpTestsStrapon;

/**
 * The TestHelper class contains functions that facilitate the test suite
 */
class TestHelper
{
    /**
     * Echo out a "Hello!"
     */
    public static function SayHello(): void {
        esc_html_e( 'Hello!', 'wp-tests-strapon' );
    }
}
