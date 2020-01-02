<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Container\Cases;

use Countable;
use Edoger\Container\Container;
use PHPUnit\Framework\TestCase;
use Edoger\Util\Contracts\Arrayable;

class ContainerTest extends TestCase
{
    public function testContainerInstanceOfArrayable()
    {
        $this->assertInstanceOf(Arrayable::class, new Container());
    }

    public function testContainerInstanceOfCountable()
    {
        $this->assertInstanceOf(Countable::class, new Container());
    }

    public function testContainerHas()
    {
        $container = new Container(['foo' => 'foo']);

        $this->assertTrue($container->has('foo'));
        $this->assertFalse($container->has('bar'));
    }

    public function testContainerGet()
    {
        $container = new Container(['foo' => 'foo']);

        $this->assertEquals('foo', $container->get('foo'));
        $this->assertNull($container->get('bar'));
        $this->assertEquals('bar', $container->get('bar', 'bar'));
    }

    public function testContainerArrayable()
    {
        $container = new Container(['foo' => 'foo']);
        $this->assertEquals(['foo' => 'foo'], $container->toArray());

        $container = new Container(['foo']);
        $this->assertEquals(['foo'], $container->toArray());
    }

    public function testContainerCountable()
    {
        $this->assertEquals(0, count(new Container()));
        $this->assertEquals(1, count(new Container(['foo'])));
        $this->assertEquals(1, count(new Container(['foo' => 'foo'])));
    }
}
