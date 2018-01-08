<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger\Tests\Cases\Handlers;

use Closure;
use RuntimeException;
use Edoger\Logger\Log;
use Edoger\Logger\Levels;
use PHPUnit\Framework\TestCase;
use Edoger\Logger\AbstractHandler;
use Edoger\Logger\Handlers\CallableHandler;

class CallableHandlerTest extends TestCase
{
    public function testCallableHandlerExtendsAbstractHandler()
    {
        $handler = new CallableHandler(function () {});

        $this->assertInstanceOf(AbstractHandler::class, $handler);
    }

    public function testCallableHandlerGetHandler()
    {
        $callable = function () {};
        $handler = new CallableHandler($callable);

        $this->assertEquals($callable, $handler->getHandler());

        $callable = 'var_dump';
        $handler  = new CallableHandler($callable);

        $this->assertEquals($callable, $handler->getHandler());
    }

    public function testCallableHandle()
    {
        $callable = function (string $channel, Log $log, Closure $next) {
            return 'test' === $channel;
        };
        $handler = new CallableHandler($callable);

        $this->assertTrue($handler->handle('test', new Log(Levels::DEBUG, 'test'), function () {}));
        $this->assertFalse($handler->handle('foo', new Log(Levels::DEBUG, 'test'), function () {}));
    }

    public function testCallableHandleFail()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The log callable handler must return a boolean value.');

        $callable = function (string $channel, Log $log, Closure $next) {
            return 'test';
        };
        $handler = new CallableHandler($callable);

        $handler->handle('test', new Log(Levels::DEBUG, 'test'), function () {}); // exception
    }
}
