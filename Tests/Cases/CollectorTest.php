<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event\Tests\Cases;

use Edoger\Event\Collector;
use Edoger\Event\Contracts\Collector as CollectorContract;
use Edoger\Event\Dispatcher;
use Edoger\Event\DispatcherContainer;
use Edoger\Event\Tests\Support\TestListener;
use Edoger\Event\Traits\CollectorSupport;
use PHPUnit\Framework\TestCase;

class CollectorTest extends TestCase
{
    public function testCollectorUseTraitCollectorSupport()
    {
        $dispatcher = new Dispatcher();
        $collector  = new Collector($dispatcher);
        $uses       = class_uses($collector);

        $this->assertArrayHasKey(CollectorSupport::class, $uses);
        $this->assertEquals(CollectorSupport::class, $uses[CollectorSupport::class]);
    }

    public function testCollectorExtendsDispatcherContainer()
    {
        $dispatcher = new Dispatcher();
        $collector  = new Collector($dispatcher);

        $this->assertInstanceOf(DispatcherContainer::class, $collector);
    }

    public function testCollectorInstanceOfCollectorContract()
    {
        $dispatcher = new Dispatcher();
        $collector  = new Collector($dispatcher);

        $this->assertInstanceOf(CollectorContract::class, $collector);
    }

    public function testCollectorGetEventDispatcher()
    {
        $dispatcher = new Dispatcher();
        $collector  = new Collector($dispatcher);

        $this->assertInstanceOf(Dispatcher::class, $collector->getEventDispatcher());
        $this->assertEquals($dispatcher, $collector->getEventDispatcher());
    }

    public function testCollectorOn()
    {
        $listener   = new TestListener();
        $dispatcher = new Dispatcher();
        $collector  = new Collector($dispatcher);

        $this->assertEquals($collector, $collector->on('test', $listener));
        $this->assertEquals([$listener], $dispatcher->getListeners('test'));
    }
}
