<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger\Tests\Cases;

use Edoger\Logger\Levels;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class LevelsTest extends TestCase
{
    protected $levels = [
        100 => 'DEBUG',
        150 => 'INFO',
        200 => 'NOTICE',
        250 => 'WARNING',
        300 => 'ERROR',
        350 => 'CRITICAL',
        400 => 'ALERT',
        450 => 'EMERGENCY',
    ];

    public function testLevelsConstants()
    {
        foreach ($this->levels as $level => $name) {
            $this->assertTrue(defined(Levels::class.'::'.$name));
        }
    }

    public function testLevelsConstantValue()
    {
        foreach ($this->levels as $level => $name) {
            $this->assertEquals($level, constant(Levels::class.'::'.$name));
        }
    }

    public function testLevelsIsLevel()
    {
        foreach ($this->levels as $level => $name) {
            $this->assertTrue(Levels::isLevel($level));
        }

        $this->assertFalse(Levels::isLevel(9999));
    }

    public function testLevelsGetLevelName()
    {
        foreach ($this->levels as $level => $name) {
            $this->assertEquals($name, Levels::getLevelName($level));
        }
    }

    public function testLevelsGetLevelNameFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The given log level is invalid.');

        Levels::getLevelName(9999);
    }

    public function testLevelsIsLevelName()
    {
        foreach ($this->levels as $level => $name) {
            $this->assertTrue(Levels::isLevelName(strtolower($name)));
            $this->assertTrue(Levels::isLevelName(strtoupper($name)));
        }

        $this->assertFalse(Levels::isLevelName('NON'));
        $this->assertFalse(Levels::isLevelName('non'));
    }

    public function testLevelsGetLevel()
    {
        foreach ($this->levels as $level => $name) {
            $this->assertEquals($level, Levels::getLevel($name));
        }
    }

    public function testLevelsGetLevelFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The given log level name is invalid.');

        Levels::getLevel('NON');
    }
}
