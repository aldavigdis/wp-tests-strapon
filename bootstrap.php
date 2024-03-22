<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use Aldavigdis\WpTestsStrapon\Bootstrap;
use Aldavigdis\WpTestsStrapon\SetEnv;

SetEnv::supress();
SetEnv::setWpVersion();
SetEnv::setConfigFilePath();

Bootstrap::init(getenv('WP_VERSION'));
Bootstrap::requireWordPressTestEnv();
