<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Flow\Cases;

use Countable;
use Edoger\Flow\Flow;
use RuntimeException;
use Edoger\Flow\AbstractFlow;
use PHPUnit\Framework\TestCase;
use Edoger\Util\Contracts\Arrayable;
use EdogerTests\Flow\Mocks\TestBlocker;
use EdogerTests\Flow\Mocks\TestProcessor;

class FlowBaseTest extends TestCase
{
    protected $blocker;

    protected function setUp()
    {
        $this->blocker = new TestBlocker();
    }

    protected function tearDown()
    {
        $this->blocker = null;
    }

    protected function createFlow()
    {
        return new Flow($this->blocker);
    }

    protected function createFlowProcessor(array $map = [])
    {
        return new TestProcessor($map);
    }

    public function testFlowExtendsAbstractFlow()
    {
        $flow = $this->createFlow();

        $this->assertInstanceOf(AbstractFlow::class, $flow);
    }

    public function testFlowInstanceOfArrayable()
    {
        $flow = $this->createFlow();

        $this->assertInstanceOf(Arrayable::class, $flow);
    }

    public function testFlowInstanceOfCountable()
    {
        $flow = $this->createFlow();

        $this->assertInstanceOf(Countable::class, $flow);
    }

    public function testFlowGetFlowBlocker()
    {
        $flow = $this->createFlow();

        $this->assertEquals($this->blocker, $flow->getFlowBlocker());
    }

    public function testFlowIsEmpty()
    {
        $flow      = $this->createFlow();
        $processor = $this->createFlowProcessor();

        $this->assertTrue($flow->isEmpty()); // default
        $flow->append($processor);
        $this->assertFalse($flow->isEmpty());
    }

    public function testFlowAppend()
    {
        $flow      = $this->createFlow();
        $processor = $this->createFlowProcessor();

        $this->assertEquals(1, $flow->append($processor));
        $this->assertEquals(2, $flow->append($processor));
        $this->assertEquals(3, $flow->append($processor));
        $this->assertEquals(4, $flow->append($processor, true));
        $this->assertEquals(5, $flow->append($processor, true));
        $this->assertEquals(6, $flow->append($processor, true));
    }

    public function testFlowRemove()
    {
        $flow       = $this->createFlow();
        $processors = [
            $this->createFlowProcessor([1]),
            $this->createFlowProcessor([2]),
            $this->createFlowProcessor([3]),
            $this->createFlowProcessor([4]),
            $this->createFlowProcessor([5]),
            $this->createFlowProcessor([6]),
        ];

        foreach ($processors as $processor) {
            $flow->append($processor);
        }

        $this->assertEquals($processors[0], $flow->remove());
        $this->assertEquals($processors[1], $flow->remove());
        $this->assertEquals($processors[2], $flow->remove());
        $this->assertEquals($processors[5], $flow->remove(false));
        $this->assertEquals($processors[4], $flow->remove(false));
        $this->assertEquals($processors[3], $flow->remove(false));
    }

    public function testFlowRemoveFail()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to remove processor from empty processor container.');

        $this->createFlow()->remove(); // exception
    }

    public function testFlowClear()
    {
        $flow = $this->createFlow();

        $flow->append($this->createFlowProcessor());
        $flow->append($this->createFlowProcessor());

        $this->assertFalse($flow->isEmpty());
        $this->assertEquals($flow, $flow->clear());
        $this->assertTrue($flow->isEmpty());
    }

    public function testFlowArrayable()
    {
        $flow       = $this->createFlow();
        $processorA = $this->createFlowProcessor([1]);
        $processorB = $this->createFlowProcessor([2]);
        $processorC = $this->createFlowProcessor([3]);

        $this->assertEquals([], $flow->toArray());

        $flow->append($processorA);
        $this->assertEquals([$processorA], $flow->toArray());

        $flow->append($processorB);
        $this->assertEquals([$processorA, $processorB], $flow->toArray());

        $flow->append($processorC, true);
        $this->assertEquals([$processorC, $processorA, $processorB], $flow->toArray());
    }

    public function testFlowCountable()
    {
        $flow = $this->createFlow();

        $this->assertEquals(0, count($flow));

        $flow->append($this->createFlowProcessor());
        $this->assertEquals(1, count($flow));
    }
}
