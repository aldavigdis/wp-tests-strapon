<?php

declare(strict_types=1);

namespace Aldavigdis\WpTestsStrapon;

use Aldavigdis\WpTestsStrapon\Config;

class Cleanup
{
    public static function deleteConfig()
    {
        if (unlink(Config::path())) {
            return true;
        }

        return false;
    }
}
