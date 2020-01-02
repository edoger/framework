<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Event\Cases;

use Edoger\Event\Event;
use Edoger\Event\Emitter;
use Edoger\Event\Dispatcher;
use PHPUnit\Framework\TestCase;
use Edoger\Event\Contracts\Trigger;
use Edoger\Event\Contracts\Collector;
use Edoger\Event\DispatcherContainer;
use Edoger\Event\Traits\TriggerSupport;
use Edoger\Event\Traits\CollectorSupport;
use EdogerTests\Event\Mocks\TestListener;

class EmitterTest extends TestCase
{
    protected $dispatcher;
    protected $emitter;

    protected function setUp()
    {
        $this->dispatcher = new Dispatcher([], 'group');
        $this->emitter    = new Emitter($this->dispatcher);
    }

    protected function tearDown()
    {
        $this->dispatcher = null;
        $this->emitter    = null;
    }

    public function testEmitterUseTraitCollectorSupport()
    {
        $dispatcher = new Dispatcher();
        $emitter    = new Emitter($dispatcher);
        $uses       = class_uses($emitter);

        $this->assertArrayHasKey(CollectorSupport::class, $uses);
        $this->assertEquals(CollectorSupport::class, $uses[CollectorSupport::class]);
    }

    public function testEmitterUseTraitTriggerSupport()
    {
        $dispatcher = new Dispatcher();
        $emitter    = new Emitter($dispatcher);
        $uses       = class_uses($emitter);

        $this->assertArrayHasKey(TriggerSupport::class, $uses);
        $this->assertEquals(TriggerSupport::class, $uses[TriggerSupport::class]);
    }

    public function testEmitterExtendsDispatcherContainer()
    {
        $dispatcher = new Dispatcher();
        $emitter    = new Emitter($dispatcher);

        $this->assertInstanceOf(DispatcherContainer::class, $emitter);
    }

    public function testEmitterInstanceOfCollector()
    {
        $dispatcher = new Dispatcher();
        $emitter    = new Emitter($dispatcher);

        $this->assertInstanceOf(Collector::class, $emitter);
    }

    public function testEmitterInstanceOfTrigger()
    {
        $dispatcher = new Dispatcher();
        $emitter    = new Emitter($dispatcher);

        $this->assertInstanceOf(Trigger::class, $emitter);
    }

    public function testEmitterGetEventDispatcher()
    {
        $dispatcher = new Dispatcher();
        $emitter    = new Emitter($dispatcher);

        $this->assertInstanceOf(Dispatcher::class, $emitter->getEventDispatcher());
        $this->assertEquals($dispatcher, $emitter->getEventDispatcher());
    }

    public function testEmitterGetSubcomponentEventName()
    {
        $dispatcher = new Dispatcher();
        $emitter    = new Emitter($dispatcher);

        $this->assertEquals('', $emitter->getSubcomponentEventName());

        $dispatcher = new Dispatcher();
        $emitter    = new Emitter($dispatcher, 'foo');

        $this->assertEquals('foo', $emitter->getSubcomponentEventName());
    }

    public function testEmitterOn()
    {
        $listener   = new TestListener();
        $dispatcher = new Dispatcher();
        $emitter    = new Emitter($dispatcher);

        $this->assertEquals($emitter, $emitter->on('test', $listener));
        $this->assertEquals([$listener], $dispatcher->getListeners('test'));
    }

    public function testEmitterHasEventListener()
    {
        $listener   = new TestListener();
        $dispatcher = new Dispatcher();
        $emitter    = new Emitter($dispatcher);

        $this->assertFalse($emitter->hasEventListener('test'));

        $dispatcher->addListener('test', $listener);

        $this->assertTrue($emitter->hasEventListener('test'));
    }

    public function testEmitterEmit()
    {
        $this->expectOutputString('TestListener');

        $listener   = new TestListener('TestListener');
        $dispatcher = new Dispatcher();
        $emitter    = new Emitter($dispatcher);

        $emitter->on('test', $listener);

        $this->assertInstanceOf(Event::class, $emitter->emit('test'));
    }
}
