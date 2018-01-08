<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow\Tests\Cases;

use Closure;
use Countable;
use Edoger\Flow\Flow;
use RuntimeException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Edoger\Flow\CallableBlocker;
use Edoger\Flow\ExceptionBlocker;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Flow\Tests\Support\TestBlocker;
use Edoger\Flow\Tests\Support\TestProcessor;

class FlowBaseTest extends TestCase
{
    public function testCreateFlowUseExceptionBlocker()
    {
        $flow = new Flow();

        $flowBlocker = call_user_func(Closure::bind(function () {
            return $this->blocker;
        }, $flow, $flow));

        $this->assertInstanceOf(ExceptionBlocker::class, $flowBlocker);
    }

    public function testCreateFlowUseClassBlocker()
    {
        $blocker = new TestBlocker();
        $flow    = new Flow($blocker);

        $flowBlocker = call_user_func(Closure::bind(function () {
            return $this->blocker;
        }, $flow, $flow));

        $this->assertEquals($blocker, $flowBlocker);
        $this->assertEquals(spl_object_hash($blocker), spl_object_hash($flowBlocker));
    }

    public function testCreateFlowUseCallableBlocker()
    {
        $blocker = function () {};
        $flow = new Flow($blocker);

        $flowBlocker = call_user_func(Closure::bind(function () {
            return $this->blocker;
        }, $flow, $flow));

        $this->assertInstanceOf(CallableBlocker::class, $flowBlocker);
        $this->assertEquals($blocker, $flowBlocker->getOriginal());
        $this->assertEquals(spl_object_hash($blocker), spl_object_hash($flowBlocker->getOriginal()));
    }

    public function testCreateFlowFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid flow blocker.');

        new Flow(false); // exception
    }

    public function testFlowInstanceOfArrayable()
    {
        $blocker = new TestBlocker();
        $flow    = new Flow($blocker);

        $this->assertInstanceOf(Arrayable::class, $flow);
    }

    public function testFlowInstanceOfCountable()
    {
        $blocker = new TestBlocker();
        $flow    = new Flow($blocker);

        $this->assertInstanceOf(Countable::class, $flow);
    }

    public function testFlowAppend()
    {
        $processor = new TestProcessor();
        $blocker   = new TestBlocker();
        $flow      = new Flow($blocker);

        $this->assertEquals(1, $flow->append($processor));
        $this->assertEquals(2, $flow->append($processor));
        $this->assertEquals(3, $flow->append($processor));
        $this->assertEquals(4, $flow->append($processor, true));
        $this->assertEquals(5, $flow->append($processor, true));
        $this->assertEquals(6, $flow->append($processor, true));
    }

    public function testFlowIsEmpty()
    {
        $processor = new TestProcessor();
        $blocker   = new TestBlocker();
        $flow      = new Flow($blocker);

        $this->assertTrue($flow->isEmpty()); // default
        $flow->append($processor);
        $this->assertFalse($flow->isEmpty());
    }

    public function testFlowRemove()
    {
        $processors = [
            new TestProcessor(),
            new TestProcessor(),
            new TestProcessor(),
            new TestProcessor(),
            new TestProcessor(),
            new TestProcessor(),
        ];

        $blocker = new TestBlocker();
        $flow    = new Flow($blocker);

        foreach ($processors as $processor) {
            $flow->append($processor);
        }

        $processor = $flow->remove();
        $this->assertEquals($processors[0], $processor);
        $this->assertEquals(spl_object_hash($processors[0]), spl_object_hash($processor));
        $processor = $flow->remove();
        $this->assertEquals($processors[1], $processor);
        $this->assertEquals(spl_object_hash($processors[1]), spl_object_hash($processor));
        $processor = $flow->remove();
        $this->assertEquals($processors[2], $processor);
        $this->assertEquals(spl_object_hash($processors[2]), spl_object_hash($processor));
        $processor = $flow->remove(false);
        $this->assertEquals($processors[5], $processor);
        $this->assertEquals(spl_object_hash($processors[5]), spl_object_hash($processor));
        $processor = $flow->remove(false);
        $this->assertEquals($processors[4], $processor);
        $this->assertEquals(spl_object_hash($processors[4]), spl_object_hash($processor));
        $processor = $flow->remove(false);
        $this->assertEquals($processors[3], $processor);
        $this->assertEquals(spl_object_hash($processors[3]), spl_object_hash($processor));
    }

    public function testFlowRemoveFail()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to remove processor from empty processor container.');

        $blocker = new TestBlocker();
        $flow    = new Flow($blocker);

        $flow->remove();
    }

    public function testFlowClear()
    {
        $processor = new TestProcessor();
        $blocker   = new TestBlocker();
        $flow      = new Flow($blocker);

        $flow->append($processor);

        $this->assertFalse($flow->isEmpty());
        $this->assertEquals($flow, $flow->clear());
        $this->assertTrue($flow->isEmpty());
    }

    public function testFlowArrayable()
    {
        $processorA = new TestProcessor();
        $processorB = new TestProcessor();
        $processorC = new TestProcessor();
        $blocker    = new TestBlocker();
        $flow       = new Flow($blocker);

        $this->assertEquals([], $flow->toArray());

        $flow->append($processorA);
        $this->assertEquals([$processorA], $flow->toArray());
        $this->assertEquals(
            array_map('spl_object_hash', [$processorA]),
            array_map('spl_object_hash', $flow->toArray())
        );

        $flow->append($processorB);
        $this->assertEquals([$processorA, $processorB], $flow->toArray());
        $this->assertEquals(
            array_map('spl_object_hash', [$processorA, $processorB]),
            array_map('spl_object_hash', $flow->toArray())
        );

        $flow->append($processorC, true);
        $this->assertEquals([$processorC, $processorA, $processorB], $flow->toArray());
        $this->assertEquals(
            array_map('spl_object_hash', [$processorC, $processorA, $processorB]),
            array_map('spl_object_hash', $flow->toArray())
        );
    }

    public function testFlowCountable()
    {
        $processor = new TestProcessor();
        $blocker   = new TestBlocker();
        $flow      = new Flow($blocker);

        $this->assertEquals(0, count($flow));
        $flow->append($processor);
        $this->assertEquals(1, count($flow));
    }
}
