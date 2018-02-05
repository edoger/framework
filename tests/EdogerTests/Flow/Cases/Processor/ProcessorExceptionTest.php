<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Flow\Cases\Processor;

use Exception;
use Edoger\Flow\Flow;
use PHPUnit\Framework\TestCase;
use Edoger\Flow\Contracts\Processor;
use EdogerTests\Flow\Mocks\TestExceptionProcessor;
use EdogerTests\Flow\Mocks\TestReturnExceptionBlocker;

class ProcessorExceptionTest extends TestCase
{
    public function testWithCallableBlocker()
    {
        $processor = new TestExceptionProcessor();
        $flow      = new Flow(function ($input, $exception) {
            return $exception;
        });

        $flow->append($processor);

        $exception = $flow->start(['name' => 'processor']);

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertEquals('ProcessorException', $exception->getMessage());
    }

    public function testWithClassBlocker()
    {
        $processor = new TestExceptionProcessor();
        $flow      = new Flow(new TestReturnExceptionBlocker());

        $flow->append($processor);

        $exception = $flow->start(['name' => 'processor']);

        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertEquals('ProcessorException', $exception->getMessage());
    }

    public function testFlowProcessor()
    {
        $processorA = new TestExceptionProcessor('A');
        $processorB = new TestExceptionProcessor('B');
        $processorC = new TestExceptionProcessor('C');
        $processorD = new TestExceptionProcessor('D');
        $processorE = new TestExceptionProcessor('E');
        $processorF = new TestExceptionProcessor('F');

        $flow = new Flow(new TestReturnExceptionBlocker());

        $flow->append($processorA);
        $flow->append($processorB);
        $flow->append($processorC);
        $flow->append($processorD, true);
        $flow->append($processorE, true);
        $flow->append($processorF, true);

        $return = $flow->start(['name' => 'processor']);

        $this->assertInstanceOf(Exception::class, $return);
        $this->assertEquals('F', $return->getMessage());
    }
}
