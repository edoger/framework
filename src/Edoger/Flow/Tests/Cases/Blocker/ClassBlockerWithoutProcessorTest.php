<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow\Tests\Cases\Blocker;

use Exception;
use Edoger\Flow\Flow;
use Edoger\Container\Container;
use PHPUnit\Framework\TestCase;
use Edoger\Flow\Contracts\Blocker;
use Edoger\Flow\Tests\Support\TestBlocker;
use Edoger\Flow\Tests\Support\TestExceptionBlocker;
use Edoger\Flow\Tests\Support\TestReturnInputBlocker;

class ClassBlockerWithoutProcessorTest extends TestCase
{
    public function testWithDefaultInput()
    {
        $blocker = new TestBlocker();
        $flow    = new Flow($blocker);

        $this->assertEquals('Blocker', $flow->start());
    }

    public function testExceptionWithDefaultInput()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('BlockerException');

        $blocker = new TestExceptionBlocker();
        $flow    = new Flow($blocker);

        $flow->start(); // exception
    }

    public function testWithUserInput()
    {
        $input   = new Container(['foo']);
        $blocker = new TestReturnInputBlocker();
        $flow    = new Flow($blocker);

        $container = $flow->start($input);

        $this->assertEquals($input, $container);
        $this->assertEquals(['foo'], $container->toArray());
    }

    public function testExceptionWithUserInput()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('BlockerException');

        $input   = new Container(['foo']);
        $blocker = new TestExceptionBlocker();
        $flow    = new Flow($blocker);

        $flow->start($input); // exception
    }
}
