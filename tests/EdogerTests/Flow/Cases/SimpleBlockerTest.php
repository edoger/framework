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
use Edoger\Flow\SimpleBlocker;
use Edoger\Container\Container;
use PHPUnit\Framework\TestCase;

class SimpleBlockerTest extends TestCase
{
    public function createSimpleBlocker()
    {
        return new SimpleBlocker();
    }

    public function testSimpleBlockerBlock()
    {
        $blocker = $this->createSimpleBlocker();

        $this->assertInstanceOf(Container::class, $blocker->block(new Container(), 'test'));

        $input = new Container();
        $this->assertEquals(
            ['input' => $input, 'result' => 'test'],
            $blocker->block($input, 'test')->toArray()
        );

        $input = new Container(['foo' => 'foo']);
        $this->assertEquals(
            ['input' => $input, 'result' => 'test'],
            $blocker->block($input, 'test')->toArray()
        );
    }

    public function testSimpleBlockerComplete()
    {
        $blocker = $this->createSimpleBlocker();

        $this->assertInstanceOf(Container::class, $blocker->complete(new Container()));

        $input = new Container(['foo' => 'foo']);
        $this->assertEquals(
            ['input' => $input],
            $blocker->complete($input)->toArray()
        );
    }

    public function testSimpleBlockerError()
    {
        $blocker = $this->createSimpleBlocker();

        $this->assertInstanceOf(Container::class, $blocker->error(new Container(), new Exception()));

        $exception = new Exception('foo');

        $input = new Container();
        $this->assertEquals(
            ['input' => $input, 'exception' => $exception],
            $blocker->error($input, $exception)->toArray()
        );

        $input = new Container(['exception' => 'foo']);
        $this->assertEquals(
            ['input' => $input, 'exception' => $exception],
            $blocker->error($input, $exception)->toArray()
        );
    }
}
