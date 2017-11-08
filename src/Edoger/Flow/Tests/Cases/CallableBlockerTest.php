<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow\Tests\Cases;

use Edoger\Container\Container;
use Edoger\Container\Wrapper;
use Edoger\Flow\CallableBlocker;
use Edoger\Flow\Contracts\Blocker;
use Exception;
use PHPUnit\Framework\TestCase;

class CallableBlockerTest extends TestCase
{
    public function testCallableBlockerExtendsWrapper()
    {
        $blocker = new CallableBlocker(function () {});

        $this->assertInstanceOf(Wrapper::class, $blocker);
    }

    public function testCallableBlockerInstanceOfBlocker()
    {
        $blocker = new CallableBlocker(function () {});

        $this->assertInstanceOf(Blocker::class, $blocker);
    }

    public function testCallableBlockerReturnInput()
    {
        $input   = new Container();
        $blocker = new CallableBlocker(function ($input, $exception) {
            return $input;
        });

        $this->assertEquals($input, $blocker->block($input));
        $this->assertEquals(spl_object_hash($input), spl_object_hash($blocker->block($input)));
    }

    public function testCallableBlockerReturnException()
    {
        $exception = new Exception();
        $blocker   = new CallableBlocker(function ($input, $exception) {
            return $exception;
        });

        $this->assertNull($blocker->block(new Container()));
        $this->assertEquals($exception, $blocker->block(new Container(), $exception));
        $this->assertEquals(spl_object_hash($exception), spl_object_hash($blocker->block(new Container(), $exception)));
    }
}
