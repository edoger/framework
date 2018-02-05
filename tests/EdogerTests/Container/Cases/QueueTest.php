<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Container\Cases;

use Countable;
use RuntimeException;
use IteratorAggregate;
use Edoger\Container\Queue;
use PHPUnit\Framework\TestCase;
use Edoger\Util\Contracts\Arrayable;

class QueueTest extends TestCase
{
    public function testQueueInstanceOfArrayable()
    {
        $this->assertInstanceOf(Arrayable::class, new Queue());
    }

    public function testQueueInstanceOfCountable()
    {
        $this->assertInstanceOf(Countable::class, new Queue());
    }

    public function testQueueInstanceOfIteratorAggregate()
    {
        $this->assertInstanceOf(IteratorAggregate::class, new Queue());
    }

    public function testQueueIsEmpty()
    {
        $queue = new Queue();
        $this->assertTrue($queue->isEmpty());

        $queue = new Queue(1);
        $this->assertFalse($queue->isEmpty());
    }

    public function testQueueEnqueue()
    {
        $queue = new Queue();

        $this->assertEquals(1, $queue->enqueue(1));
        $this->assertEquals(2, $queue->enqueue(1));
        $this->assertEquals(3, $queue->enqueue(1));
    }

    public function testQueueDequeue()
    {
        $queue = new Queue([1, 2, 3]);

        $this->assertEquals(1, $queue->dequeue());
        $this->assertEquals(2, $queue->dequeue());
        $this->assertEquals(3, $queue->dequeue());
    }

    public function testQueueDequeueFail()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to remove element from empty queue.');

        $queue = new Queue();
        $queue->dequeue();
    }

    public function testQueueClear()
    {
        $queue = new Queue([1, 2, 3]);

        $this->assertFalse($queue->isEmpty());
        $this->assertEquals($queue, $queue->clear());
        $this->assertTrue($queue->isEmpty());
    }

    public function testQueueArrayable()
    {
        $queue = new Queue();
        $this->assertEquals([], $queue->toArray());

        $queue = new Queue([1]);
        $this->assertEquals([1], $queue->toArray());
    }

    public function testQueueCountable()
    {
        $this->assertEquals(0, count(new Queue()));
        $this->assertEquals(1, count(new Queue(['foo'])));
        $this->assertEquals(1, count(new Queue(['foo' => 'foo'])));
    }

    public function testQueueIteratorAggregate()
    {
        $elements = ['foo' => 'foo', 'bar'];
        $queue    = new Queue($elements);

        $elements = array_values($elements);
        foreach ($queue as $index => $value) {
            $this->assertEquals($elements[$index], $value);
        }
    }
}
