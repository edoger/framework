<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Logger\Cases\Formatter;

use Edoger\Logger\Log;
use Edoger\Logger\Levels;
use PHPUnit\Framework\TestCase;
use Edoger\Logger\Contracts\Formatter;
use Edoger\Logger\Formatter\LineFormatter;

class LineFormatterTest extends TestCase
{
    public function testLineFormatterInstanceOfFormatter()
    {
        $formatter = new LineFormatter();

        $this->assertInstanceOf(Formatter::class, $formatter);
    }

    public function testLineFormatterFormat()
    {
        $now       = time();
        $log       = new Log(Levels::DEBUG, 'TestLineFormatter', [], $now, []);
        $formatter = new LineFormatter();
        $message   = sprintf('[CHANNEL][%s][DEBUG] TestLineFormatter', date('Y-m-d H:i:s', $now)).PHP_EOL;

        $this->assertEquals($message, $formatter->format('CHANNEL', $log));
    }

    public function testLineFormatterFormatWithDateFormat()
    {
        $now       = time();
        $log       = new Log(Levels::DEBUG, 'TestLineFormatter', [], $now, []);
        $formatter = new LineFormatter('Y-m-d');
        $message   = sprintf('[CHANNEL][%s][DEBUG] TestLineFormatter', date('Y-m-d', $now)).PHP_EOL;

        $this->assertEquals($message, $formatter->format('CHANNEL', $log));
    }

    public function testLineFormatterFormatWithLinefeed()
    {
        $now       = time();
        $log       = new Log(Levels::DEBUG, 'TestLineFormatter', [], $now, []);
        $formatter = new LineFormatter('Y-m-d', false);
        $message   = sprintf('[CHANNEL][%s][DEBUG] TestLineFormatter', date('Y-m-d', $now));

        $this->assertEquals($message, $formatter->format('CHANNEL', $log));
    }
}
