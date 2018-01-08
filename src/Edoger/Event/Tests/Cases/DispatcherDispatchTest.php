<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event\Tests\Cases;

use Edoger\Event\Event;
use Edoger\Event\Dispatcher;
use PHPUnit\Framework\TestCase;
use Edoger\Event\Tests\Support\TestListener;

class DispatcherDispatchTest extends TestCase
{
    public function testDispatchWithoutListener()
    {
        $body       = ['test' => true];
        $dispatcher = new Dispatcher();

        $event = $dispatcher->dispatch('test');
        $this->assertInstanceOf(Event::class, $event);
        $this->assertEquals([], $event->toArray());

        $event = $dispatcher->dispatch('test', $body);
        $this->assertInstanceOf(Event::class, $event);
        $this->assertEquals($body, $event->toArray());
    }

    public function testDispatchWithClassListener()
    {
        $this->expectOutputString('TestListener');

        $listener   = new TestListener('TestListener');
        $dispatcher = new Dispatcher();
        $dispatcher->addListener('test', $listener);

        $dispatcher->dispatch('test');
    }

    public function testDispatcherDispatchWithCallableListener()
    {
        $this->expectOutputString('TestListener');

        $listener = function (Event $event, Dispatcher $dispatcher) {
            echo 'TestListener';
        };
        $dispatcher = new Dispatcher();
        $dispatcher->addListener('test', $listener);

        $dispatcher->dispatch('test');
    }

    public function testDispatcherDispatchWithMultipleListeners()
    {
        $this->expectOutputString('TestListenerBTestListenerA');

        $listenerA = new TestListener('TestListenerA');
        $listenerB = function (Event $event, Dispatcher $dispatcher) {
            echo 'TestListenerB';
        };
        $dispatcher = new Dispatcher();
        $dispatcher->addListener('test', $listenerA);
        $dispatcher->addListener('test', $listenerB);

        $dispatcher->dispatch('test');
    }

    public function testDispatcherDispatchWithInterruptListener()
    {
        $this->expectOutputString('TestListenerB');

        $listenerA = new TestListener('TestListenerA');
        $listenerB = function (Event $event, Dispatcher $dispatcher) {
            echo 'TestListenerB';
        };
        $listenerC = function (Event $event, Dispatcher $dispatcher) {
            $event->interrupt();
        };

        $dispatcher = new Dispatcher();
        $dispatcher->addListener('test', $listenerA);
        $dispatcher->addListener('test', $listenerC);
        $dispatcher->addListener('test', $listenerB);

        $dispatcher->dispatch('test');
    }

    public function testDispatcherDispatchWithDisableListener()
    {
        $this->expectOutputString('TestListenerB');

        $listenerA = new TestListener('TestListenerA');
        $listenerB = function (Event $event, Dispatcher $dispatcher) {
            echo 'TestListenerB';
        };
        $listenerC = function (Event $event, Dispatcher $dispatcher) {
            $dispatcher->disable('test');
        };

        $dispatcher = new Dispatcher();
        $dispatcher->addListener('test', $listenerA);
        $dispatcher->addListener('test', $listenerC);
        $dispatcher->addListener('test', $listenerB);

        $dispatcher->dispatch('test');
    }
}
