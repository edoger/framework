<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Event\Cases;

use Edoger\Event\Factory;
use Edoger\Event\Dispatcher;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testFactoryCreateDispatcher()
    {
        $dispatcher = Factory::createDispatcher();
        $this->assertInstanceOf(Dispatcher::class, $dispatcher);
        $this->assertEquals('', $dispatcher->getEventGroupName());

        $dispatcher = Factory::createDispatcher('test');
        $this->assertInstanceOf(Dispatcher::class, $dispatcher);
        $this->assertEquals('test', $dispatcher->getEventGroupName());
    }

    public function testFactoryCreateEdogerDispatcher()
    {
        $dispatcher = Factory::createEdogerDispatcher();
        $this->assertInstanceOf(Dispatcher::class, $dispatcher);
        $this->assertEquals('edoger', $dispatcher->getEventGroupName());

        $dispatcher = Factory::createEdogerDispatcher('test');
        $this->assertInstanceOf(Dispatcher::class, $dispatcher);
        $this->assertEquals('edoger.test', $dispatcher->getEventGroupName());
    }
}
