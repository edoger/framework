<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger\Tests\Cases;

use Edoger\Logger\Levels;
use Edoger\Logger\Log;
use PHPUnit\Framework\TestCase;

class LogTest extends TestCase
{
    public function testLogIsHandled()
    {
        $log = new Log(Levels::DEBUG, 'test');
        $this->assertFalse($log->isHandled());
    }

    public function testLogToHandled()
    {
        $log = new Log(Levels::DEBUG, 'test');
        $this->assertFalse($log->isHandled());
        $this->assertEquals($log, $log->toHandled());
        $this->assertTrue($log->isHandled());
    }

    public function testLogGetLevel()
    {
        $log = new Log(Levels::DEBUG, 'test');
        $this->assertEquals(Levels::DEBUG, $log->getLevel());
    }

    public function testLogGetLevelName()
    {
        $log = new Log(Levels::DEBUG, 'test');
        $this->assertEquals(Levels::getLevelName(Levels::DEBUG), $log->getLevelName());
    }

    public function testLogGetMessage()
    {
        $log = new Log(Levels::DEBUG, 'test');
        $this->assertEquals('test', $log->getMessage());
    }

    public function testLogGetContext()
    {
        $log = new Log(Levels::DEBUG, 'test');
        $this->assertEquals([], $log->getContext());

        $log = new Log(Levels::DEBUG, 'test', ['test']);
        $this->assertEquals(['test'], $log->getContext());
    }

    public function testLogGetTimestamp()
    {
        $now = time();
        $log = new Log(Levels::DEBUG, 'test', [], $now);
        $this->assertEquals($now, $log->getTimestamp());

        $log = new Log(Levels::DEBUG, 'test', ['test']); // default
        $this->assertGreaterThanOrEqual($now, $log->getTimestamp());
    }

    public function testLogGetDatetime()
    {
        $now = time();
        $log = new Log(Levels::DEBUG, 'test', [], $now);
        $this->assertEquals(date('Y-m-d H:i:s', $now), $log->getDatetime());
        $this->assertEquals(date('Y-m-d', $now), $log->getDatetime('Y-m-d'));
    }

    public function testLogIsEmptyExtras()
    {
        $log = new Log(Levels::DEBUG, 'test', [], time()); // default
        $this->assertTrue($log->isEmptyExtras());

        $log = new Log(Levels::DEBUG, 'test', [], time(), []);
        $this->assertTrue($log->isEmptyExtras());

        $log = new Log(Levels::DEBUG, 'test', [], time(), ['test']);
        $this->assertFalse($log->isEmptyExtras());
    }

    public function testLogHasExtra()
    {
        $log = new Log(Levels::DEBUG, 'test', [], time(), ['test' => 'test']);
        $this->assertTrue($log->hasExtra('test'));
        $this->assertFalse($log->hasExtra('non'));
    }

    public function testLogGetExtra()
    {
        $log = new Log(Levels::DEBUG, 'test', [], time(), ['test' => 'test']);
        $this->assertEquals('test', $log->getExtra('test'));
        $this->assertNull($log->getExtra('non'));
        $this->assertEquals('test', $log->getExtra('non', 'test'));
    }

    public function testLogGetExtras()
    {
        $log = new Log(Levels::DEBUG, 'test', [], time()); // default
        $this->assertEquals([], $log->getExtras());

        $log = new Log(Levels::DEBUG, 'test', [], time(), ['test' => 'test']);
        $this->assertEquals(['test' => 'test'], $log->getExtras());
    }

    public function testLogSetExtra()
    {
        $log = new Log(Levels::DEBUG, 'test', [], time());
        $this->assertEquals([], $log->getExtras());
        $log->setExtra('test', 'test');
        $this->assertEquals(['test' => 'test'], $log->getExtras());
        $log->setExtra('test', 'foo');
        $this->assertEquals(['test' => 'foo'], $log->getExtras());
    }

    public function testLogReplaceExtras()
    {
        $log = new Log(Levels::DEBUG, 'test', [], time(), ['test' => 'test']);
        $this->assertEquals(['test' => 'test'], $log->getExtras());
        $this->assertEquals($log, $log->replaceExtras([]));
        $this->assertEquals([], $log->getExtras([]));
        $this->assertEquals($log, $log->replaceExtras(['test' => 'test']));
        $this->assertEquals(['test' => 'test'], $log->getExtras());
    }

    public function testLogDeleteExtra()
    {
        $log = new Log(Levels::DEBUG, 'test', [], time(), ['test' => 'test']);
        $this->assertEquals(['test' => 'test'], $log->getExtras());
        $log->deleteExtra('test');
        $this->assertEquals([], $log->getExtras());
    }

    public function testLogClearExtras()
    {
        $log = new Log(Levels::DEBUG, 'test', [], time(), ['test' => 'test']);
        $this->assertEquals(['test' => 'test'], $log->getExtras());
        $this->assertEquals($log, $log->clearExtras());
        $this->assertEquals([], $log->getExtras([]));
    }

    public function testLogCountExtras()
    {
        $log = new Log(Levels::DEBUG, 'test', [], time());
        $this->assertEquals(0, $log->countExtras());

        $log = new Log(Levels::DEBUG, 'test', [], time(), ['test' => 'test']);
        $this->assertEquals(1, $log->countExtras());
        $this->assertEquals(0, $log->clearExtras()->countExtras());
    }
}
