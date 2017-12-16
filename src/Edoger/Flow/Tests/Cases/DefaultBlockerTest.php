<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow\Tests\Cases;

use Exception;
use Edoger\Container\Container;
use Edoger\Flow\DefaultBlocker;
use PHPUnit\Framework\TestCase;
use Edoger\Flow\Contracts\Blocker;

class DefaultBlockerTest extends TestCase
{
    public function testDefaultBlockerInstanceOfBlocker()
    {
        $blocker = new DefaultBlocker();

        $this->assertInstanceOf(Blocker::class, $blocker);
    }

    public function testDefaultBlockerReturnInput()
    {
        $input   = new Container();
        $blocker = new DefaultBlocker();

        $this->assertNull($blocker->block($input));
    }

    public function testDefaultBlockerReturnException()
    {
        $input     = new Container();
        $exception = new Exception();
        $blocker   = new DefaultBlocker();

        $this->assertEquals($exception, $blocker->block($input, $exception));
        $this->assertEquals(spl_object_hash($exception), spl_object_hash($blocker->block($input, $exception)));
    }
}
