<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger\Tests\Cases;

use Exception;
use Edoger\Logger\Blocker;
use Edoger\Container\Container;
use PHPUnit\Framework\TestCase;
use Edoger\Flow\Contracts\Blocker as BlockerContract;

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
