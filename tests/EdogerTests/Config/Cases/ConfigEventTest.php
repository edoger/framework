<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Config\Cases;

use Exception;
use Edoger\Event\Event;
use Edoger\Config\Config;
use Edoger\Event\Dispatcher;
use Edoger\Config\Repository;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use EdogerTests\Config\Mocks\TestLoader;
use EdogerTests\Config\Mocks\TestExceptionLoader;

class ConfigEventTest extends TestCase
{
    public function testLoadingEvent()
    {
        $loader = new TestLoader();
        $config = new Config([$loader]);

        $config->on('loading', function (Event $event, Dispatcher $dispatcher) {
            $this->assertEquals('config.loading', $event->getName());
            $this->assertEquals(['group' => 'test', 'reload' => false], $event->toArray());
        });

        $config->group('test');
    }

    public function testLoadingEventWithReload()
    {
        $loader = new TestLoader();
        $config = new Config([$loader]);

        $config->group('test'); // loading

        $config->on('loading', function (Event $event, Dispatcher $dispatcher) {
            $this->assertEquals('config.loading', $event->getName());
            $this->assertEquals(['group' => 'test', 'reload' => true], $event->toArray());
        });

        $config->group('test', true);
    }

    public function testLoadedEvent()
    {
        $loader = new TestLoader('test', [true]);
        $config = new Config([$loader]);

        $config->on('loaded', function (Event $event, Dispatcher $dispatcher) {
            $this->assertEquals('config.loaded', $event->getName());
            $this->assertEquals('test', $event->get('group'));
            $this->assertFalse($event->get('reload'));
            $this->assertEquals(3, count($event));

            $repository = $event->get('repository');

            $this->assertInstanceOf(Repository::class, $repository);
            $this->assertEquals([true], $repository->toArray());
        });

        $config->group('test');
    }

    public function testLoadedEventWithReload()
    {
        $loader = new TestLoader('test', [true]);
        $config = new Config([$loader]);

        $config->group('test'); // loading

        $config->on('loaded', function (Event $event, Dispatcher $dispatcher) {
            $this->assertEquals('config.loaded', $event->getName());
            $this->assertEquals('test', $event->get('group'));
            $this->assertTrue($event->get('reload'));
            $this->assertEquals(3, count($event));

            $repository = $event->get('repository');

            $this->assertInstanceOf(Repository::class, $repository);
            $this->assertEquals([true], $repository->toArray());
        });

        $config->group('test', true);
    }

    public function testMissedEvent()
    {
        $config = new Config();

        $config->on('missed', function (Event $event, Dispatcher $dispatcher) {
            $this->assertEquals('config.missed', $event->getName());
            $this->assertEquals(['group' => 'test', 'reload' => false], $event->toArray());
        });

        $config->group('test');
    }

    public function testMissedEventWithReload()
    {
        $config = new Config();

        $config->group('test'); // missed

        $config->on('missed', function (Event $event, Dispatcher $dispatcher) {
            $this->assertEquals('config.missed', $event->getName());
            $this->assertEquals(['group' => 'test', 'reload' => true], $event->toArray());
        });

        $config->group('test', true);
    }

    public function testErrorEvent()
    {
        $loader = new TestExceptionLoader();
        $config = new Config([$loader]);

        $config->on('error', function (Event $event, Dispatcher $dispatcher) {
            $this->assertEquals('config.error', $event->getName());
            $this->assertEquals('test', $event->get('group'));
            $this->assertFalse($event->get('reload'));
            $this->assertEquals(3, count($event));

            $exception = $event->get('exception');

            $this->assertInstanceOf(Exception::class, $exception);
            $this->assertEquals('test', $exception->getMessage());
        });

        $config->group('test');
    }

    public function testErrorEventByEmptyGroupName()
    {
        $loader = new TestExceptionLoader();
        $config = new Config([$loader]);

        $config->on('error', function (Event $event, Dispatcher $dispatcher) {
            $this->assertEquals('config.error', $event->getName());
            $this->assertEquals('', $event->get('group'));
            $this->assertFalse($event->get('reload'));
            $this->assertEquals(3, count($event));

            $exception = $event->get('exception');

            $this->assertInstanceOf(InvalidArgumentException::class, $exception);
            $this->assertEquals('Invalid configuration group name.', $exception->getMessage());
        });

        $config->group('');
    }

    public function testErrorEventWithReload()
    {
        $loader = new TestExceptionLoader();
        $config = new Config([$loader]);

        $config->group('test'); // error

        $config->on('error', function (Event $event, Dispatcher $dispatcher) {
            $this->assertEquals('config.error', $event->getName());
            $this->assertEquals('test', $event->get('group'));
            $this->assertTrue($event->get('reload'));
            $this->assertEquals(3, count($event));

            $exception = $event->get('exception');

            $this->assertInstanceOf(Exception::class, $exception);
            $this->assertEquals('test', $exception->getMessage());
        });

        $config->group('test', true);
    }
}
