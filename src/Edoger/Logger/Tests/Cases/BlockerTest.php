<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger\Tests\Cases;

use Edoger\Container\Container;
use Edoger\Flow\Contracts\Blocker as BlockerContract;
use Edoger\Logger\Blocker;
use Exception;
use PHPUnit\Framework\TestCase;

class BlockerTest extends TestCase
{
    public function testBlockerInstanceOfBlockerContract()
    {
        $blocker = new Blocker();

        $this->assertInstanceOf(BlockerContract::class, $blocker);
    }

    public function testBlockerBlock()
    {
        $blocker   = new Blocker();
        $container = new Container();
        $exception = new Exception('test');

        $this->assertTrue($blocker->block($container));
        $this->assertFalse($blocker->block($container, $exception));
    }
}
