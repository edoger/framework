<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Config\Cases;

use Exception;
use Edoger\Config\Blocker;
use Edoger\Config\Repository;
use Edoger\Container\Container;
use PHPUnit\Framework\TestCase;
use Edoger\Flow\Contracts\Blocker as BlockerContract;

class BlockerTest extends TestCase
{
    protected function createBlocker()
    {
        return new Blocker();
    }

    public function testBlockerInstanceOfBlockerContract()
    {
        $blocker = $this->createBlocker();

        $this->assertInstanceOf(BlockerContract::class, $blocker);
    }

    public function testBlockerBlock()
    {
        $blocker    = $this->createBlocker();
        $container  = new Container();
        $repository = new Repository(['foo' => 'foo']);

        $this->assertEquals($repository, $blocker->block($container, $repository));
    }

    public function testBlockerComplete()
    {
        $blocker   = $this->createBlocker();
        $container = new Container();

        $this->assertInstanceOf(Repository::class, $blocker->complete($container));
        $this->assertEquals([], $blocker->complete($container)->toArray());
    }

    public function testBlockerError()
    {
        $blocker   = $this->createBlocker();
        $container = new Container();
        $exception = new Exception('test');

        $this->assertInstanceOf(Repository::class, $blocker->error($container, $exception));
        $this->assertEquals([], $blocker->error($container, $exception)->toArray());
    }
}
