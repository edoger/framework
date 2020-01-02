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
use Edoger\Event\Trigger;
use Edoger\Event\Dispatcher;
use PHPUnit\Framework\TestCase;
use Edoger\Event\DispatcherContainer;
use Edoger\Event\Traits\TriggerSupport;
use EdogerTests\Event\Mocks\TestListener;
use Edoger\Event\Contracts\Trigger as TriggerContract;

class TriggerTest extends TestCase
{
    public function testTriggerUseTraitTriggerSupport()
    {
        $dispatcher = new Dispatcher();
        $trigger    = new Trigger($dispatcher);
        $uses       = class_uses($trigger);

        $this->assertArrayHasKey(TriggerSupport::class, $uses);
        $this->assertEquals(TriggerSupport::class, $uses[TriggerSupport::class]);
    }

    public function testTriggerExtendsDispatcherContainer()
    {
        $dispatcher = new Dispatcher();
        $trigger    = new Trigger($dispatcher);

        $this->assertInstanceOf(DispatcherContainer::class, $trigger);
    }

    public function testTriggerInstanceOfTriggerContract()
    {
        $dispatcher = new Dispatcher();
        $trigger    = new Trigger($dispatcher);

        $this->assertInstanceOf(TriggerContract::class, $trigger);
    }

    public function testTriggerGetEventDispatcher()
    {
        $dispatcher = new Dispatcher();
        $trigger    = new Trigger($dispatcher);

        $this->assertInstanceOf(Dispatcher::class, $trigger->getEventDispatcher());
        $this->assertEquals($dispatcher, $trigger->getEventDispatcher());
    }

    public function testTriggerGetSubcomponentEventName()
    {
        $dispatcher = new Dispatcher();
        $trigger    = new Trigger($dispatcher);

        $this->assertEquals('', $trigger->getSubcomponentEventName());

        $dispatcher = new Dispatcher();
        $trigger    = new Trigger($dispatcher, 'foo');

        $this->assertEquals('foo', $trigger->getSubcomponentEventName());
    }

    public function testTriggerHasEventListener()
    {
        $listener   = new TestListener();
        $dispatcher = new Dispatcher();
        $trigger    = new Trigger($dispatcher);

        $this->assertFalse($trigger->hasEventListener('test'));

        $dispatcher->addListener('test', $listener);

        $this->assertTrue($trigger->hasEventListener('test'));
    }

    public function testTriggerEmit()
    {
        $this->expectOutputString('TestListener');

        $listener   = new TestListener('TestListener');
        $dispatcher = new Dispatcher();
        $trigger    = new Trigger($dispatcher);

        $dispatcher->addListener('test', $listener);

        $this->assertInstanceOf(Event::class, $trigger->emit('test'));
    }
}
