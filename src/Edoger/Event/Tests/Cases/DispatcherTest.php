<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event\Tests\Cases;

use Edoger\Event\Dispatcher;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Event\Tests\Support\TestListener;

class DispatcherTest extends TestCase
{
    public function testDispatcherInstanceOfArrayable()
    {
        $dispatcher = new Dispatcher();

        $this->assertInstanceOf(Arrayable::class, $dispatcher);
    }

    public function testDispatcherGetEventGroupName()
    {
        $dispatcher = new Dispatcher();
        $this->assertEquals('', $dispatcher->getEventGroupName());

        $dispatcher = new Dispatcher([], 'group');
        $this->assertEquals('group', $dispatcher->getEventGroupName());
    }

    public function testDispatcherStandardizeEventName()
    {
        $dispatcher = new Dispatcher();
        $this->assertEquals('test', $dispatcher->standardizeEventName('test'));

        $dispatcher = new Dispatcher([], 'group');
        $this->assertEquals('group.test', $dispatcher->standardizeEventName('test'));
    }

    public function testDispatcherIsEnabled()
    {
        $listener   = new TestListener();
        $dispatcher = new Dispatcher(['test' => $listener]);

        $this->assertTrue($dispatcher->isEnabled('test'));
        $this->assertFalse($dispatcher->isEnabled('non'));
    }

    public function testDispatcherEnable()
    {
        $listener   = new TestListener();
        $dispatcher = new Dispatcher(['test' => $listener]);

        $this->assertEquals($dispatcher, $dispatcher->enable('test'));
        $this->assertTrue($dispatcher->isEnabled('test'));
    }

    public function testDispatcherDisable()
    {
        $listener   = new TestListener();
        $dispatcher = new Dispatcher(['test' => $listener]);

        $this->assertEquals($dispatcher, $dispatcher->disable('test'));
        $this->assertFalse($dispatcher->isEnabled('test'));
        $this->assertEquals($dispatcher, $dispatcher->enable('test'));
        $this->assertTrue($dispatcher->isEnabled('test'));
    }

    public function testDispatcherIsEmptyListeners()
    {
        $listener = new TestListener();

        $dispatcher = new Dispatcher();
        $this->assertTrue($dispatcher->isEmptyListeners('test'));
        $this->assertTrue($dispatcher->isEmptyListeners('non'));

        $dispatcher = new Dispatcher(['test' => $listener]);
        $this->assertFalse($dispatcher->isEmptyListeners('test'));
        $this->assertTrue($dispatcher->isEmptyListeners('non'));
    }

    public function testDispatcherGetListeners()
    {
        $listenerA = new TestListener('A');
        $listenerB = new TestListener('B');
        $listenerC = function () {};

        $dispatcher = new Dispatcher();
        $this->assertEquals([], $dispatcher->getListeners('test'));

        $dispatcher = new Dispatcher(['test' => $listenerA]);
        $this->assertEquals([$listenerA], $dispatcher->getListeners('test'));

        $dispatcher = new Dispatcher(['test' => [$listenerA, $listenerB]]);
        $this->assertEquals([$listenerA, $listenerB], $dispatcher->getListeners('test'));

        $dispatcher = new Dispatcher(['test' => [$listenerA, $listenerB, $listenerC]]);
        $this->assertEquals([$listenerA, $listenerB, $listenerC], $dispatcher->getListeners('test'));
    }

    public function testDispatcherAddListeners()
    {
        $listenerA = new TestListener('A');
        $listenerB = new TestListener('B');
        $listenerC = function () {};

        $dispatcher = new Dispatcher();

        $this->assertEquals(1, $dispatcher->addListener('test', $listenerA));
        $this->assertEquals(2, $dispatcher->addListener('test', $listenerB));
        $this->assertEquals(3, $dispatcher->addListener('test', $listenerC));
        $this->assertEquals([$listenerA, $listenerB, $listenerC], $dispatcher->getListeners('test'));
    }

    public function testDispatcherAddListenersFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid event listener.');

        $dispatcher = new Dispatcher();

        $dispatcher->addListener('test', false); // exception
    }

    public function testDispatcherRemoveListener()
    {
        $listenerA = new TestListener('A');
        $listenerB = function () {};

        $dispatcher = new Dispatcher(['test' => [$listenerA, $listenerB]]);

        $this->assertEquals($listenerB, $dispatcher->removeListener('test'));
        $this->assertEquals($listenerA, $dispatcher->removeListener('test'));
        $this->assertNull($dispatcher->removeListener('test'));
    }

    public function testDispatcherClearListeners()
    {
        $listenerA = new TestListener('A');
        $listenerB = function () {};

        $dispatcher = new Dispatcher(['test' => [$listenerA, $listenerB]]);

        $this->assertEquals([$listenerA, $listenerB], $dispatcher->getListeners('test'));
        $this->assertEquals($dispatcher, $dispatcher->clearListeners('test'));
        $this->assertEquals([], $dispatcher->getListeners('test'));
    }

    public function testDispatcherCountListeners()
    {
        $listener = new TestListener();

        $dispatcher = new Dispatcher();
        $this->assertEquals(0, $dispatcher->countListeners('test'));

        $dispatcher = new Dispatcher(['test' => $listener]);
        $this->assertEquals(1, $dispatcher->countListeners('test'));
    }

    public function testDispatcherArrayable()
    {
        $listenerA = new TestListener('A');
        $listenerB = function () {};

        $dispatcher = new Dispatcher();
        $this->assertEquals([], $dispatcher->toArray());

        $dispatcher = new Dispatcher();
        $dispatcher->addListener('test', $listenerA);
        $dispatcher->addListener('test', $listenerB);
        $this->assertEquals(['test' => [$listenerA, $listenerB]], $dispatcher->toArray());

        $dispatcherCopy = new Dispatcher($dispatcher);
        $this->assertEquals(['test' => [$listenerA, $listenerB]], $dispatcherCopy->toArray());

        $dispatcher = new Dispatcher([], 'group');
        $dispatcher->addListener('test', $listenerA);
        $dispatcher->addListener('test', $listenerB);
        $this->assertEquals(['test' => [$listenerA, $listenerB]], $dispatcher->toArray());

        $dispatcherCopy = new Dispatcher($dispatcher, 'groupCopy');
        $this->assertEquals(['test' => [$listenerA, $listenerB]], $dispatcherCopy->toArray());
    }
}
