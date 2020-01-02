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
use RuntimeException;
use IteratorAggregate;
use Edoger\Container\Stack;
use PHPUnit\Framework\TestCase;
use Edoger\Util\Contracts\Arrayable;

class StackTest extends TestCase
{
    public function testStackInstanceOfArrayable()
    {
        $this->assertInstanceOf(Arrayable::class, new Stack());
    }

    public function testStackInstanceOfCountable()
    {
        $this->assertInstanceOf(Countable::class, new Stack());
    }

    public function testStackInstanceOfIteratorAggregate()
    {
        $this->assertInstanceOf(IteratorAggregate::class, new Stack());
    }

    public function testStackIsEmpty()
    {
        $stack = new Stack();
        $this->assertTrue($stack->isEmpty());

        $stack = new Stack(1);
        $this->assertFalse($stack->isEmpty());
    }

    public function testStackPush()
    {
        $stack = new Stack();

        $this->assertEquals(1, $stack->push(1));
        $this->assertEquals(2, $stack->push(1));
        $this->assertEquals(3, $stack->push(1));
    }

    public function testStackPop()
    {
        $stack = new Stack([1, 2, 3]);

        $this->assertEquals(3, $stack->pop());
        $this->assertEquals(2, $stack->pop());
        $this->assertEquals(1, $stack->pop());
    }

    public function testStackPopFail()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to remove element from empty stack.');

        $stack = new Stack();
        $stack->pop();
    }

    public function testStackClear()
    {
        $stack = new Stack([1, 2, 3]);

        $this->assertFalse($stack->isEmpty());
        $this->assertEquals($stack, $stack->clear());
        $this->assertTrue($stack->isEmpty());
    }

    public function testStackArrayable()
    {
        $stack = new Stack();
        $this->assertEquals([], $stack->toArray());

        $stack = new Stack([1]);
        $this->assertEquals([1], $stack->toArray());

        $stack = new Stack([1, 2]);
        $this->assertEquals([2, 1], $stack->toArray());

        $stack = new Stack([1, 2, 3, 4]);
        $this->assertEquals([4, 3, 2, 1], $stack->toArray());
    }

    public function testStackCountable()
    {
        $this->assertEquals(0, count(new Stack()));
        $this->assertEquals(1, count(new Stack(['foo'])));
        $this->assertEquals(1, count(new Stack(['foo' => 'foo'])));
    }

    public function testStackIteratorAggregate()
    {
        $elements = ['foo' => 'foo', 'bar'];
        $stack    = new Stack($elements);

        $elements = array_reverse(array_values($elements));
        foreach ($stack as $index => $value) {
            $this->assertEquals($elements[$index], $value);
        }
    }
}
