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
return __DIR__.'/../vendor/autoload.php';
