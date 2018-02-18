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
    protected function createConfig(iterable $loaders = [])
    {
        return new Config($loaders);
    }

    protected function createLoader(string $group = 'test', array $value = [])
    {
        return new TestLoader($group, $value);
    }

    public function testLoadingEvent()
    {
        $config = $this->createConfig([$this->createLoader()]);

        $config->on('loading', function (Event $event, Dispatcher $dispatcher) {
            $this->assertEquals('config.loading', $event->getName());
            $this->assertEquals(['group' => 'test', 'reload' => false], $event->toArray());
        });

        $config->group('test');
    }

    public function testLoadingEventWithReload()
    {
        $config = $this->createConfig([$this->createLoader()]);

        $config->group('test'); // loading

        $config->on('loading', function (Event $event, Dispatcher $dispatcher) {
            $this->assertEquals('config.loading', $event->getName());
            $this->assertEquals(['group' => 'test', 'reload' => true], $event->toArray());
        });

        $config->group('test', true);
    }

    public function testLoadedEvent()
    {
        $config = $this->createConfig([$this->createLoader('test', [true])]);

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
        $config = $this->createConfig([$this->createLoader('test', [true])]);

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
        $config = $this->createConfig();

        $config->on('missed', function (Event $event, Dispatcher $dispatcher) {
            $this->assertEquals('config.missed', $event->getName());
            $this->assertEquals(['group' => 'test', 'reload' => false], $event->toArray());
        });

        $config->group('test');
    }

    public function testMissedEventWithReload()
    {
        $config = $this->createConfig();

        $config->group('test'); // missed

        $config->on('missed', function (Event $event, Dispatcher $dispatcher) {
            $this->assertEquals('config.missed', $event->getName());
            $this->assertEquals(['group' => 'test', 'reload' => true], $event->toArray());
        });

        $config->group('test', true);
    }

    public function testErrorEvent()
    {
        $config = $this->createConfig([new TestExceptionLoader()]);

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

    public function testErrorEventWithReload()
    {
        $config = $this->createConfig([new TestExceptionLoader()]);

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
