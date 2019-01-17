<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Flow\Cases;

use Edoger\Container\Queue;
use Edoger\Flow\ProcessorQueue;
use PHPUnit\Framework\TestCase;

class ProcessorQueueTest extends TestCase
{
    public function testProcessorQueueExtendsQueue()
    {
        $queue = new ProcessorQueue();

        $this->assertInstanceOf(Queue::class, $queue);
    }

    public function testProcessorIsCompleted()
    {
        $queue = new ProcessorQueue();

        $this->assertFalse($queue->isCompleted());
    }

    public function testProcessorToCompleted()
    {
        $queue = new ProcessorQueue();

        $queue->toCompleted();
        $this->assertTrue($queue->isCompleted());
    }

    public function testProcessorIsFailed()
    {
        $queue = new ProcessorQueue();

        $this->assertFalse($queue->isFailed());
    }

    public function testProcessorToFailed()
    {
        $queue = new ProcessorQueue();

        $queue->toFailed();
        $this->assertTrue($queue->isFailed());
    }
}
