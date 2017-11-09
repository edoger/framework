<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event\Tests\Cases;

use Edoger\Container\Wrapper;
use Edoger\Event\Contracts\DispatcherContainer as DispatcherContainerContract;
use Edoger\Event\Dispatcher;
use Edoger\Event\DispatcherContainer;
use PHPUnit\Framework\TestCase;

class DispatcherContainerTest extends TestCase
{
    public function testDispatcherContainerExtendsWrapper()
    {
        $dispatcher = new Dispatcher();
        $container  = new DispatcherContainer($dispatcher);

        $this->assertInstanceOf(Wrapper::class, $container);
    }

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
}
