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
use Edoger\Container\Wrapper;
use PHPUnit\Framework\TestCase;
use Edoger\Event\CallableListener;
use Edoger\Event\Contracts\Listener;

class CallableListenerTest extends TestCase
{
    public function testCallableListenerExtendsWrapper()
    {
        $listener = new CallableListener(function () {});

        $this->assertInstanceOf(Wrapper::class, $listener);
    }

    public function testCallableListenerInstanceOfListener()
    {
        $listener = new CallableListener(function () {});

        $this->assertInstanceOf(Listener::class, $listener);
    }

    public function testCallableListenerHandle()
    {
        $event      = new Event('test');
        $dispatcher = new Dispatcher();
        $listener   = new CallableListener(
            function ($listenerEvent, $listenerDispatcher) use ($event, $dispatcher) {
                $this->assertInstanceOf(Event::class, $listenerEvent);
                $this->assertInstanceOf(Dispatcher::class, $listenerDispatcher);
                $this->assertEquals($event, $listenerEvent);
                $this->assertEquals($dispatcher, $listenerDispatcher);
            }
        );

        $listener->handle($event, $dispatcher);
    }
}
