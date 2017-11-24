<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event\Tests\Cases;

use Edoger\Event\Event;
use PHPUnit\Framework\TestCase;
use Edoger\Container\Collection;

class EventTest extends TestCase
{
    public function testEventExtendsCollection()
    {
        $event = new Event('test');

        $this->assertInstanceOf(Collection::class, $event);
    }

    public function testEventGetName()
    {
        $event = new Event('test');
        $this->assertEquals('test', $event->getName());

        $event = new Event('test', 'group');
        $this->assertEquals('test', $event->getName());
    }

    public function testEventGetGroupName()
    {
        $event = new Event('test');
        $this->assertEquals('', $event->getGroupName());

        $event = new Event('test', 'group');
        $this->assertEquals('group', $event->getGroupName());
    }

    public function testEventGetFullName()
    {
        $event = new Event('test');
        $this->assertEquals('test', $event->getFullName());

        $event = new Event('test', 'group');
        $this->assertEquals('group.test', $event->getFullName());
    }

    public function testEventIsInterrupted()
    {
        $event = new Event('test');

        $this->assertFalse($event->isInterrupted());
    }

    public function testEventInterrupt()
    {
        $event = new Event('test');

        $this->assertEquals($event, $event->interrupt());
        $this->assertTrue($event->isInterrupted());
    }
}
