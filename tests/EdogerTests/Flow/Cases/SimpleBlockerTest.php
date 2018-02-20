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
        $this->assertEquals(
            ['result' => 'test'],
            $blocker->block(new Container(), 'test')->toArray()
        );
        $this->assertEquals(
            ['foo' => 'foo', 'result' => 'test'],
            $blocker->block(new Container(['foo' => 'foo']), 'test')->toArray()
        );
        $this->assertEquals(
            ['result' => 'test'],
            $blocker->block(new Container(['result' => 'foo']), 'test')->toArray()
        );
    }

    public function testSimpleBlockerComplete()
    {
        $blocker = $this->createSimpleBlocker();

        $this->assertInstanceOf(Container::class, $blocker->complete(new Container()));
        $this->assertEquals(
            ['foo' => 'foo'],
            $blocker->complete(new Container(['foo' => 'foo']))->toArray()
        );
    }

    public function testSimpleBlockerError()
    {
        $blocker = $this->createSimpleBlocker();

        $this->assertInstanceOf(Container::class, $blocker->error(new Container(), new Exception()));

        $exception = new Exception('foo');

        $this->assertEquals(
            ['exception' => $exception],
            $blocker->error(new Container(), $exception)->toArray()
        );
        $this->assertEquals(
            ['exception' => $exception],
            $blocker->error(new Container(['exception' => 'foo']), $exception)->toArray()
        );
    }
}
