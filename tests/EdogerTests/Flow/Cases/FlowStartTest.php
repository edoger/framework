<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Flow\Cases;

use Exception;
use Throwable;
use Edoger\Flow\Flow;
use Edoger\Container\Container;
use PHPUnit\Framework\TestCase;
use EdogerTests\Flow\Mocks\TestBlocker;
use EdogerTests\Flow\Mocks\TestProcessor;
use EdogerTests\Flow\Mocks\TestEmptyProcessor;
use EdogerTests\Flow\Mocks\TestExceptionProcessor;

class FlowStartTest extends TestCase
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

    protected function assertStartReturnedFromComplete($return, array $input)
    {
        $this->assertTrue(is_array($return));
        $this->assertArrayHasKey(0, $return);
        $this->assertArrayHasKey(1, $return);
        $this->assertArrayHasKey(2, $return);
        $this->assertCount(3, $return);
        $this->assertEquals('complete', $return[0]);
        $this->assertInstanceOf(Container::class, $return[1]);
        $this->assertEquals($input, $return[1]->toArray());
        $this->assertNull($return[2]);
    }

    protected function assertStartReturnedFromBlock($return, array $input, $result)
    {
        $this->assertTrue(is_array($return));
        $this->assertArrayHasKey(0, $return);
        $this->assertArrayHasKey(1, $return);
        $this->assertArrayHasKey(2, $return);
        $this->assertCount(3, $return);
        $this->assertEquals('block', $return[0]);
        $this->assertInstanceOf(Container::class, $return[1]);
        $this->assertEquals($input, $return[1]->toArray());
        $this->assertEquals($result, $return[2]);
    }

    protected function assertStartReturnedFromError($return, array $input, string $abstract, string $message)
    {
        $this->assertTrue(is_array($return));
        $this->assertArrayHasKey(0, $return);
        $this->assertArrayHasKey(1, $return);
        $this->assertArrayHasKey(2, $return);
        $this->assertCount(3, $return);
        $this->assertEquals('error', $return[0]);
        $this->assertInstanceOf(Container::class, $return[1]);
        $this->assertEquals($input, $return[1]->toArray());
        $this->assertInstanceOf(Throwable::class, $return[2]);
        $this->assertInstanceOf($abstract, $return[2]);
        $this->assertEquals($message, $return[2]->getMessage());
    }

    public function testFlowStartReturnedFromComplete()
    {
        $flow = $this->createFlow();
        $flow->append($this->createFlowProcessor(['foo' => 'foo']));
        $flow->append($this->createFlowProcessor(['bar' => 'bar']));

        $return = $flow->start(['key' => 'test']);

        $this->assertStartReturnedFromComplete($return, ['key' => 'test']);
    }

    public function testFlowStartWithoutProcessor()
    {
        $flow   = $this->createFlow();
        $return = $flow->start(['key' => 'test']);

        $this->assertStartReturnedFromComplete($return, ['key' => 'test']);
    }

    public function testFlowStartReturnedFromBlock()
    {
        $flow = $this->createFlow();
        $flow->append($this->createFlowProcessor(['foo' => 'foo']));
        $flow->append($this->createFlowProcessor(['bar' => 'bar']));

        $return = $flow->start(['key' => 'bar']);

        $this->assertStartReturnedFromBlock($return, ['key' => 'bar'], 'bar');
    }

    public function testFlowStartReturnedFromError()
    {
        $flow = $this->createFlow();
        $flow->append($this->createFlowProcessor(['foo' => 'foo']));
        $flow->append(new TestExceptionProcessor(['bar' => 'bar']));

        $return = $flow->start(['key' => 'bar']);

        $this->assertStartReturnedFromError($return, ['key' => 'bar'], Exception::class, 'bar');
    }

    public function testFlowStartWithEmptyProcessor()
    {
        $flow = $this->createFlow();
        $flow->append(new TestEmptyProcessor());
        $flow->append(new TestEmptyProcessor());
        $flow->append(new TestEmptyProcessor());

        $return = $flow->start(['key' => 'test']);

        $this->assertStartReturnedFromComplete($return, ['key' => 'test']);
    }
}
