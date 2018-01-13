<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

// The test environment.
// This is to be compatible with the Travis-CI test environment and the local test environment.
define('TEST_ENV', $_ENV['TEST_ENV'] ?? $_SERVER['TEST_ENV'] ?? 'LOCAL');

// Enable Composer autoloader.
require __DIR__.'/../vendor/autoload.php';

// Define test MySQL database account.
if ('TRAVIS_CI' === TEST_ENV) {
    define('TEST_MYSQL_USERNAME', 'edoger');
    define('TEST_MYSQL_PASSWORD', 'edoger');
} elseif ('LOCAL' === TEST_ENV) {
    define('TEST_MYSQL_USERNAME', $_ENV['TEST_MYSQL_USERNAME'] ?? $_SERVER['TEST_MYSQL_USERNAME'] ?? 'edoger');
    define('TEST_MYSQL_PASSWORD', $_ENV['TEST_MYSQL_PASSWORD'] ?? $_SERVER['TEST_MYSQL_PASSWORD'] ?? 'edoger');
} else {
    echo 'Invalid test environment.'.PHP_EOL;
    // End the test, but this is not considered a test failure.
    exit(0);
}
