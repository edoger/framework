<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Config\Cases;

use Exception;
use Edoger\Event\Trigger;
use Edoger\Config\Blocker;
use Edoger\Event\Dispatcher;
use Edoger\Config\Repository;
use Edoger\Container\Wrapper;
use Edoger\Container\Container;
use PHPUnit\Framework\TestCase;
use Edoger\Flow\Contracts\Blocker as BlockerContract;

class BlockerTest extends TestCase
{
    protected $dispatcher;
    protected $trigger;

    protected function setUp()
    {
        $this->dispatcher = new Dispatcher();
        $this->trigger    = new Trigger($this->dispatcher);
    }

    protected function tearDown()
    {
        $this->dispatcher = null;
        $this->trigger    = null;
    }

    protected function createBlocker()
    {
        return new Blocker($this->trigger);
    }

    public function testBlockerExtendsWrapper()
    {
        $blocker = $this->createBlocker();

        $this->assertInstanceOf(Wrapper::class, $blocker);
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
        $missed    = false;

        $this->assertInstanceOf(Repository::class, $blocker->complete($container));
        $this->assertEquals([], $blocker->complete($container)->toArray());

        $this->dispatcher->addListener('missed', function () use (&$missed) {
            $missed = true;
        });

        $blocker->complete($container);

        $this->assertTrue($missed);
    }

    public function testBlockerError()
    {
        $blocker   = $this->createBlocker();
        $container = new Container();
        $exception = new Exception('test');
        $error     = false;

        $this->assertInstanceOf(Repository::class, $blocker->error($container, $exception));
        $this->assertEquals([], $blocker->error($container, $exception)->toArray());

        $this->dispatcher->addListener('error', function () use (&$error) {
            $error = true;
        });

        $blocker->error($container, $exception);

        $this->assertTrue($error);
    }
}
