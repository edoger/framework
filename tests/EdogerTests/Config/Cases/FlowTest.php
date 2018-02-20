<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Config\Cases;

use Edoger\Config\Flow;
use Edoger\Flow\SimpleBlocker;
use PHPUnit\Framework\TestCase;
use Edoger\Flow\Flow as BaseFlow;

class FlowTest extends TestCase
{
    public function testFlowExtendsBaseFlow()
    {
        $flow = new Flow(new SimpleBlocker());

        $this->assertInstanceOf(BaseFlow::class, $flow);
    }
}
