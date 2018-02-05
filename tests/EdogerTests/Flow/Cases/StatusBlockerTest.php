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
use Edoger\Flow\StatusBlocker;
use Edoger\Container\Container;
use PHPUnit\Framework\TestCase;
use Edoger\Flow\Contracts\Blocker;

class StatusBlockerTest extends TestCase
{
    public function testStatusBlockerInstanceOfBlocker()
    {
        $blocker = new StatusBlocker();

        $this->assertInstanceOf(Blocker::class, $blocker);
    }

    public function testStatusBlockerReturnInput()
    {
        $input   = new Container();
        $blocker = new StatusBlocker();

        $this->assertEquals(StatusBlocker::STATUS_SUCCESS, $blocker->block($input));
    }

    public function testStatusBlockerReturnException()
    {
        $input     = new Container();
        $exception = new Exception();
        $blocker   = new StatusBlocker();

        $this->assertEquals(StatusBlocker::STATUS_FAILURE, $blocker->block($input, $exception));
    }
}
