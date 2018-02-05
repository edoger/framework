<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Util\Cases;

use Edoger\Util\Environment;
use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    public function testEnvironmentIsWindows()
    {
        if ('\\' === DIRECTORY_SEPARATOR) {
            $this->assertTrue(Environment::isWindows());
        } else {
            $this->assertFalse(Environment::isWindows());
        }
    }

    public function testEnvironmentIsCli()
    {
        if ('cli' === PHP_SAPI) {
            $this->assertTrue(Environment::isCli());
        } else {
            $this->assertFalse(Environment::isCli());
        }
    }
}
