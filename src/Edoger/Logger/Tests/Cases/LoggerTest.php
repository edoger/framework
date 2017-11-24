<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger\Tests\Cases;

use RuntimeException;
use Edoger\Logger\Log;
use Edoger\Logger\Levels;
use Edoger\Logger\Logger;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Edoger\Logger\Handlers\CallableHandler;
use Edoger\Logger\Tests\Support\TestHandler;
use Edoger\Logger\Tests\Support\TestExceptionHandler;

class LoggerTest extends TestCase
{
    public function testLoggerIsEmptyHandlers()
    {
        $handler = new TestHandler();
        $logger  = new Logger('CHANNEL');

        $this->assertTrue($logger->isEmptyHandlers());
        $logger->pushHandler($handler);
        $this->assertFalse($logger->isEmptyHandlers());
    }

    public function testLoggerCountHandlers()
    {
        $handler = new TestHandler();
        $logger  = new Logger('CHANNEL');

        $this->assertEquals(0, $logger->countHandlers());
        $logger->pushHandler($handler);
        $this->assertEquals(1, $logger->countHandlers());
    }

    public function testLoggerGetHandlers()
    {
        $handler = new TestHandler();
        $logger  = new Logger('CHANNEL');

        $this->assertEquals([], $logger->getHandlers());
        $logger->pushHandler($handler);
        $this->assertEquals([$handler], $logger->getHandlers());
    }

    public function testLoggerPushHandler()
    {
        $handlerA = new TestHandler(true);
        $handlerB = new TestHandler(false);
        $handlerC = new TestHandler(true);
        $logger   = new Logger('CHANNEL');

        $this->assertEquals(1, $logger->pushHandler($handlerA));
        $this->assertEquals([$handlerA], $logger->getHandlers());

        $this->assertEquals(2, $logger->pushHandler($handlerB));
        $this->assertEquals([$handlerA, $handlerB], $logger->getHandlers());

        $this->assertEquals(3, $logger->pushHandler($handlerC, false));
        $this->assertEquals([$handlerC, $handlerA, $handlerB], $logger->getHandlers());
    }

    public function testLoggerPushHandlerWithCallable()
    {
        $handler = function () {};
        $logger = new Logger('CHANNEL');

        $this->assertEquals(1, $logger->pushHandler($handler));

        $handlers = $logger->getHandlers();

        $this->assertInstanceOf(CallableHandler::class, $handlers[0]);
        $this->assertEquals($handler, $handlers[0]->getHandler());
    }

    public function testLoggerPushHandlerFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid log handler.');

        $logger = new Logger('CHANNEL');

        $logger->pushHandler(false); // exception
    }

    public function testLoggerPopHandler()
    {
        $handlerA = new TestHandler(true);
        $handlerB = new TestHandler(false);
        $handlerC = new TestHandler(true);
        $logger   = new Logger('CHANNEL');

        $logger->pushHandler($handlerA);
        $logger->pushHandler($handlerB);
        $logger->pushHandler($handlerC, false);

        // [B, A, C]
        $this->assertEquals($handlerB, $logger->popHandler());
        $this->assertEquals($handlerC, $logger->popHandler(false));
        $this->assertEquals($handlerA, $logger->popHandler());
    }

    public function testLoggerPopHandlerFail()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to remove log handler from the empty log handle stack.');

        $logger = new Logger('CHANNEL');

        $logger->popHandler(); // exception
    }

    public function testLoggerClearHandlers()
    {
        $handler = new TestHandler();
        $logger  = new Logger('CHANNEL');

        $logger->pushHandler($handler);
        $logger->pushHandler($handler);

        $this->assertEquals(2, $logger->countHandlers());
        $this->assertEquals($logger, $logger->clearHandlers());
        $this->assertEquals(0, $logger->countHandlers());
    }

    public function testLoggerLog()
    {
        $logger = new Logger('CHANNEL');

        $this->assertTrue($logger->log(Levels::NOTICE, 'test'));
    }

    public function testLoggerLogWithHandler()
    {
        $handler = new TestHandler(false);
        $logger  = new Logger('CHANNEL');

        $logger->pushHandler($handler);

        $this->assertFalse($logger->log(Levels::NOTICE, 'test'));
    }

    public function testLoggerLogWithExceptionHandler()
    {
        $handler = new TestExceptionHandler();
        $logger  = new Logger('CHANNEL');

        $logger->pushHandler($handler);

        $this->assertFalse($logger->log(Levels::NOTICE, 'test'));
    }

    public function testLoggerLogWithLevel()
    {
        $logger = new Logger('CHANNEL', Levels::ERROR);

        $this->assertFalse($logger->log(Levels::DEBUG, 'test'));
        $this->assertFalse($logger->log(Levels::INFO, 'test'));
        $this->assertFalse($logger->log(Levels::NOTICE, 'test'));
        $this->assertFalse($logger->log(Levels::WARNING, 'test'));
        $this->assertTrue($logger->log(Levels::ERROR, 'test'));
        $this->assertTrue($logger->log(Levels::CRITICAL, 'test'));
        $this->assertTrue($logger->log(Levels::ALERT, 'test'));
        $this->assertTrue($logger->log(Levels::EMERGENCY, 'test'));
    }

    public function testLoggerGetLogs()
    {
        $logger = new Logger('CHANNEL', Levels::DEBUG);

        $logger->log(Levels::DEBUG, 'DEBUG');
        $logger->log(Levels::INFO, 'INFO');
        $logger->log(Levels::NOTICE, 'NOTICE');
        $logger->log(Levels::WARNING, 'WARNING');
        $logger->log(Levels::ERROR, 'ERROR');
        $logger->log(Levels::CRITICAL, 'CRITICAL');
        $logger->log(Levels::ALERT, 'ALERT');
        $logger->log(Levels::EMERGENCY, 'EMERGENCY');

        $logs = $logger->getLogs();

        $this->assertCount(8, $logs);

        foreach ([
            [Levels::DEBUG, 'DEBUG'],
            [Levels::INFO, 'INFO'],
            [Levels::NOTICE, 'NOTICE'],
            [Levels::WARNING, 'WARNING'],
            [Levels::ERROR, 'ERROR'],
            [Levels::CRITICAL, 'CRITICAL'],
            [Levels::ALERT, 'ALERT'],
            [Levels::EMERGENCY, 'EMERGENCY'],
        ] as $index => [$level, $message]) {
            $this->assertEquals($level, $logs[$index]->getLevel());
            $this->assertEquals($message, $logs[$index]->getMessage());
        }
    }

    public function testLoggerGetLogsWithLevel()
    {
        $logger = new Logger('CHANNEL', Levels::ERROR);

        $logger->log(Levels::DEBUG, 'DEBUG');
        $logger->log(Levels::INFO, 'INFO');
        $logger->log(Levels::NOTICE, 'NOTICE');
        $logger->log(Levels::WARNING, 'WARNING');
        $logger->log(Levels::ERROR, 'ERROR');
        $logger->log(Levels::CRITICAL, 'CRITICAL');
        $logger->log(Levels::ALERT, 'ALERT');
        $logger->log(Levels::EMERGENCY, 'EMERGENCY');

        $logs = $logger->getLogs();

        $this->assertCount(4, $logs);

        foreach ([
            [Levels::ERROR, 'ERROR'],
            [Levels::CRITICAL, 'CRITICAL'],
            [Levels::ALERT, 'ALERT'],
            [Levels::EMERGENCY, 'EMERGENCY'],
        ] as $index => [$level, $message]) {
            $this->assertEquals($level, $logs[$index]->getLevel());
            $this->assertEquals($message, $logs[$index]->getMessage());
        }
    }

    public function testLoggerGetLogsWithFilter()
    {
        $logger = new Logger('CHANNEL', Levels::DEBUG);

        $logger->log(Levels::DEBUG, 'DEBUG');
        $logger->log(Levels::INFO, 'INFO');
        $logger->log(Levels::NOTICE, 'NOTICE');
        $logger->log(Levels::WARNING, 'WARNING');
        $logger->log(Levels::ERROR, 'ERROR');
        $logger->log(Levels::CRITICAL, 'CRITICAL');
        $logger->log(Levels::ALERT, 'ALERT');
        $logger->log(Levels::EMERGENCY, 'EMERGENCY');

        $logs = $logger->getLogs(function (Log $log) {
            return Levels::NOTICE === $log->getLevel();
        });

        $this->assertCount(1, $logs);
        $this->assertEquals(Levels::NOTICE, $logs[0]->getLevel());
        $this->assertEquals('NOTICE', $logs[0]->getMessage());
    }

    public function testLoggerDebug()
    {
        $logger = new Logger('CHANNEL', Levels::DEBUG);

        $logger->debug('DEBUG');

        $logs = $logger->getLogs();

        $this->assertCount(1, $logs);
        $this->assertEquals(Levels::DEBUG, $logs[0]->getLevel());
        $this->assertEquals('DEBUG', $logs[0]->getMessage());
    }

    public function testLoggerInfo()
    {
        $logger = new Logger('CHANNEL', Levels::DEBUG);

        $logger->info('INFO');

        $logs = $logger->getLogs();

        $this->assertCount(1, $logs);
        $this->assertEquals(Levels::INFO, $logs[0]->getLevel());
        $this->assertEquals('INFO', $logs[0]->getMessage());
    }

    public function testLoggerNotice()
    {
        $logger = new Logger('CHANNEL', Levels::DEBUG);

        $logger->notice('NOTICE');

        $logs = $logger->getLogs();

        $this->assertCount(1, $logs);
        $this->assertEquals(Levels::NOTICE, $logs[0]->getLevel());
        $this->assertEquals('NOTICE', $logs[0]->getMessage());
    }

    public function testLoggerWarning()
    {
        $logger = new Logger('CHANNEL', Levels::DEBUG);

        $logger->warning('WARNING');

        $logs = $logger->getLogs();

        $this->assertCount(1, $logs);
        $this->assertEquals(Levels::WARNING, $logs[0]->getLevel());
        $this->assertEquals('WARNING', $logs[0]->getMessage());
    }

    public function testLoggerError()
    {
        $logger = new Logger('CHANNEL', Levels::DEBUG);

        $logger->error('ERROR');

        $logs = $logger->getLogs();

        $this->assertCount(1, $logs);
        $this->assertEquals(Levels::ERROR, $logs[0]->getLevel());
        $this->assertEquals('ERROR', $logs[0]->getMessage());
    }

    public function testLoggerCritical()
    {
        $logger = new Logger('CHANNEL', Levels::DEBUG);

        $logger->critical('CRITICAL');

        $logs = $logger->getLogs();

        $this->assertCount(1, $logs);
        $this->assertEquals(Levels::CRITICAL, $logs[0]->getLevel());
        $this->assertEquals('CRITICAL', $logs[0]->getMessage());
    }

    public function testLoggerAlert()
    {
        $logger = new Logger('CHANNEL', Levels::DEBUG);

        $logger->alert('ALERT');

        $logs = $logger->getLogs();

        $this->assertCount(1, $logs);
        $this->assertEquals(Levels::ALERT, $logs[0]->getLevel());
        $this->assertEquals('ALERT', $logs[0]->getMessage());
    }

    public function testLoggerEmergency()
    {
        $logger = new Logger('CHANNEL', Levels::DEBUG);

        $logger->emergency('EMERGENCY');

        $logs = $logger->getLogs();

        $this->assertCount(1, $logs);
        $this->assertEquals(Levels::EMERGENCY, $logs[0]->getLevel());
        $this->assertEquals('EMERGENCY', $logs[0]->getMessage());
    }
}
