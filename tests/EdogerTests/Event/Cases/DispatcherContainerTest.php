<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Event\Cases;

use Edoger\Event\Dispatcher;
use PHPUnit\Framework\TestCase;
use Edoger\Event\DispatcherContainer;
use Edoger\Event\Contracts\DispatcherContainer as DispatcherContainerContract;

class DispatcherContainerTest extends TestCase
{
    public function testDispatcherContainerInstanceOfDispatcherContainerContract()
    {
        $dispatcher = new Dispatcher();
        $container  = new DispatcherContainer($dispatcher);

        $this->assertInstanceOf(DispatcherContainerContract::class, $container);
    }

    public function testDispatcherContainerGetEventDispatcher()
    {
        $dispatcher = new Dispatcher();
        $container  = new DispatcherContainer($dispatcher);

        $this->assertInstanceOf(Dispatcher::class, $container->getEventDispatcher());
        $this->assertEquals($dispatcher, $container->getEventDispatcher());
    }

    public function testDispatcherContainerGetSubcomponentEventName()
    {
        $dispatcher = new Dispatcher();
        $container  = new DispatcherContainer($dispatcher);

        $this->assertEquals('', $container->getSubcomponentEventName());

        $dispatcher = new Dispatcher();
        $container  = new DispatcherContainer($dispatcher, 'foo');

        $this->assertEquals('foo', $container->getSubcomponentEventName());
    }
}
