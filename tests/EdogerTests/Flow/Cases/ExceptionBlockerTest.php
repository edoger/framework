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
use Edoger\Container\Container;
use PHPUnit\Framework\TestCase;
use Edoger\Flow\ExceptionBlocker;
use Edoger\Flow\Contracts\Blocker;

class ExceptionBlockerTest extends TestCase
{
    public function testExceptionBlockerInstanceOfBlocker()
    {
        $blocker = new ExceptionBlocker();

        $this->assertInstanceOf(Blocker::class, $blocker);
    }

    public function testExceptionBlockerReturnInput()
    {
        $input   = new Container();
        $blocker = new ExceptionBlocker();

        $this->assertNull($blocker->block($input));
    }

    public function testExceptionBlockerReturnException()
    {
        $input     = new Container();
        $exception = new Exception();
        $blocker   = new ExceptionBlocker();

        $this->assertEquals($exception, $blocker->block($input, $exception));
        $this->assertEquals(spl_object_hash($exception), spl_object_hash($blocker->block($input, $exception)));
    }
}
