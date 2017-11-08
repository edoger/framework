<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow\Tests\Cases\Blocker;

use Edoger\Container\Container;
use Edoger\Flow\Contracts\Blocker;
use Edoger\Flow\Flow;
use Edoger\Flow\Tests\Support\TestProcessor;
use Exception;
use PHPUnit\Framework\TestCase;

class CallableBlockerWithProcessorTest extends TestCase
{
    public function testWithDefaultInput()
    {
        $flow = new Flow(function ($input, $exception) {
            return $input;
        });

        $flow->append(new TestProcessor());

        $container = $flow->start();

        $this->assertInstanceOf(Container::class, $container);
        $this->assertEquals([], $container->toArray());
    }

    public function testExceptionWithDefaultInput()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('BlockerException');

        $flow = new Flow(function ($input, $exception) {
            throw new Exception('BlockerException');
        });

        $flow->append(new TestProcessor());
        $flow->start(); // exception
    }

    public function testWithUserInput()
    {
        $input = new Container(['foo']);
        $flow  = new Flow(function ($input, $exception) {
            return $input;
        });

        $flow->append(new TestProcessor());
        $container = $flow->start($input);

        $this->assertEquals($input, $container);
        $this->assertEquals(['foo'], $container->toArray());
    }

    public function testExceptionWithUserInput()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('BlockerException');

        $input = new Container(['foo']);
        $flow  = new Flow(function ($input, $exception) {
            throw new Exception('BlockerException');
        });

        $flow->append(new TestProcessor());
        $flow->start($input); // exception
    }
}
