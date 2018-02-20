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
use Exception;
use Edoger\Flow\Flow;
use RuntimeException;
use Edoger\Flow\AbstractFlow;
use PHPUnit\Framework\TestCase;
use Edoger\Flow\Contracts\Blocker;
use Edoger\Util\Contracts\Arrayable;
use EdogerTests\Flow\Mocks\TestBlocker;
use EdogerTests\Flow\Mocks\TestProcessor;
use Edoger\Flow\Traits\ProcessorStoreSupport;
use Edoger\Flow\Contracts\Flow as FlowContract;
use EdogerTests\Flow\Mocks\TestExceptionBlocker;
use EdogerTests\Flow\Mocks\TestExceptionProcessor;

class FlowTest extends TestCase
{
    protected function createFlow(Blocker $blocker = null)
    {
        if (is_null($blocker)) {
            $blocker = new TestBlocker();
        }

        return new Flow($blocker);
    }

    protected function createFlowExceptionBlocker()
    {
        return new TestExceptionBlocker();
    }

    protected function createFlowProcessor(array $map = [])
    {
        return new TestProcessor($map);
    }

    protected function createFlowExceptionProcessor(array $map = [])
    {
        return new TestExceptionProcessor($map);
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

    public function testFlowInstanceOfFlowContract()
    {
        $flow = $this->createFlow();

        $this->assertInstanceOf(FlowContract::class, $flow);
    }

    public function testFlowInstanceOfCountable()
    {
        $flow = $this->createFlow();

        $this->assertInstanceOf(Countable::class, $flow);
    }

    public function testFlowUseTraitProcessorStoreSupport()
    {
        $uses     = class_uses($this->createFlow());
        $abstract = ProcessorStoreSupport::class;

        $this->assertArrayHasKey($abstract, $uses);
        $this->assertEquals($abstract, $uses[$abstract]);
    }

    public function testFlowIsEmpty()
    {
        $flow = $this->createFlow();

        $this->assertTrue($flow->isEmpty());

        $flow->append($this->createFlowProcessor());
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
        $flow = $this->createFlow();

        $processorA = $this->createFlowProcessor([1]);
        $processorB = $this->createFlowProcessor([2]);
        $processorC = $this->createFlowProcessor([3]);
        $processorD = $this->createFlowProcessor([4]);
        $processorE = $this->createFlowProcessor([5]);
        $processorF = $this->createFlowProcessor([6]);

        $flow->append($processorA);
        $flow->append($processorB);
        $flow->append($processorC);
        $flow->append($processorD);
        $flow->append($processorE);
        $flow->append($processorF);

        $this->assertEquals($processorA, $flow->remove());
        $this->assertEquals($processorB, $flow->remove());
        $this->assertEquals($processorC, $flow->remove());
        $this->assertEquals($processorF, $flow->remove(false));
        $this->assertEquals($processorE, $flow->remove(false));
        $this->assertEquals($processorD, $flow->remove(false));
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

    public function testFlowStartWithoutInput()
    {
        $flow = $this->createFlow();

        $this->assertEquals(['complete', [], null], $flow->start());
    }

    public function testFlowStartWithInput()
    {
        $flow = $this->createFlow();

        $this->assertEquals(
            ['complete', ['key' => 'test'], null],
            $flow->start(['key' => 'test'])
        );
    }

    public function testFlowStartWithProcessor()
    {
        $flow = $this->createFlow();

        $flow->append($this->createFlowProcessor(['foo' => 'foo']));
        $flow->append($this->createFlowProcessor(['bar' => 'bar']));

        // without input
        $this->assertEquals(
            ['complete', [], null],
            $flow->start()
        );

        // with input
        $this->assertEquals(
            ['complete', ['key' => 'test'], null],
            $flow->start(['key' => 'test'])
        );
        $this->assertEquals(
            ['block', ['key' => 'bar'], 'bar'],
            $flow->start(['key' => 'bar'])
        );
    }

    public function testFlowStartWithExceptionProcessor()
    {
        $flow = $this->createFlow();

        $flow->append($this->createFlowExceptionProcessor(['foo' => 'foo']));

        $this->assertEquals(
            ['error', ['key' => 'foo'], Exception::class, 'foo'],
            $flow->start(['key' => 'foo'])
        );
    }

    public function testFlowStartWithExceptionBlocker()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('error');

        $flow = $this->createFlow($this->createFlowExceptionBlocker());

        $flow->append($this->createFlowProcessor(['foo' => 'foo']));
        $flow->append($this->createFlowProcessor(['bar' => 'bar']));

        $this->assertEquals(
            ['error', ['key' => 'bar', 'exception' => 'block'], Exception::class, 'block'],
            $flow->start(['key' => 'bar', 'exception' => 'block'])
        );

        $this->assertEquals(
            ['error', ['key' => 'test', 'exception' => 'complete'], Exception::class, 'complete'],
            $flow->start(['key' => 'test', 'exception' => 'complete'])
        );

        $flow->clear();
        $flow->append($this->createFlowExceptionProcessor(['foo' => 'foo']));

        $flow->start(['key' => 'foo', 'exception' => 'error']); // exception
    }

    public function testFlowArrayable()
    {
        $flow = $this->createFlow();

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
