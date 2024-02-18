<?php

declare(strict_types=1);

namespace Aldavigdis\WpTestsStrapon;

class TestHelper
{
    public static function SayHello() {
        esc_html_e( 'Hello!', 'wp-tests-strapon' );
    }
}
