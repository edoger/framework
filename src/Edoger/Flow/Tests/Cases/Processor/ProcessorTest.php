<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow\Tests\Cases\Processor;

use Edoger\Flow\Contracts\Processor;
use Edoger\Flow\Flow;
use Edoger\Flow\Tests\Support\TestBlocker;
use Edoger\Flow\Tests\Support\TestProcessor;
use PHPUnit\Framework\TestCase;

class ProcessorTest extends TestCase
{
    public function testWithCallableBlocker()
    {
        $processor = new TestProcessor();
        $flow      = new Flow(function () {
            return 'Blocker';
        });

        $flow->append($processor);

        $this->assertEquals('Processor', $flow->start(['name' => 'processor']));
        $this->assertEquals('Blocker', $flow->start(['name' => 'blocker']));
    }

    public function testWithClassBlocker()
    {
        $processor = new TestProcessor();
        $flow      = new Flow(new TestBlocker());

        $flow->append($processor);

        $this->assertEquals('Processor', $flow->start(['name' => 'processor']));
        $this->assertEquals('Blocker', $flow->start(['name' => 'blocker']));
    }

    public function testFlowProcessor()
    {
        $processorA = new TestProcessor('testA', 'A');
        $processorB = new TestProcessor('testB', 'B');
        $processorC = new TestProcessor('testC', 'C');
        $processorD = new TestProcessor('testD', 'D');
        $processorE = new TestProcessor('testE', 'E');
        $processorF = new TestProcessor('testF', 'F');

        $flow = new Flow(new TestBlocker());

        $flow->append($processorA);
        $flow->append($processorB);
        $flow->append($processorC);
        $flow->append($processorD, true);
        $flow->append($processorE, true);
        $flow->append($processorF, true);

        $this->assertEquals('A', $flow->start(['name' => 'testA']));
        $this->assertEquals('B', $flow->start(['name' => 'testB']));
        $this->assertEquals('C', $flow->start(['name' => 'testC']));
        $this->assertEquals('D', $flow->start(['name' => 'testD']));
        $this->assertEquals('E', $flow->start(['name' => 'testE']));
        $this->assertEquals('F', $flow->start(['name' => 'testF']));

        $this->assertEquals('Blocker', $flow->start(['name' => 'blocker']));
    }
}
