<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\Tests\Cases\MySQL;

use stdClass;
use Countable;
use JsonSerializable;
use IteratorAggregate;
use Edoger\Container\Container;
use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Arguments;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Database\MySQL\Exceptions\ArgumentException;

class ArgumentsTest extends TestCase
{
    public function testArgumentsInstanceOfArrayable()
    {
        $arguments = new Arguments([]);

        $this->assertInstanceOf(Arrayable::class, $arguments);
    }

    public function testArgumentsInstanceOfCountable()
    {
        $arguments = new Arguments([]);

        $this->assertInstanceOf(Countable::class, $arguments);
    }

    public function testArgumentsInstanceOfIteratorAggregate()
    {
        $arguments = new Arguments([]);

        $this->assertInstanceOf(IteratorAggregate::class, $arguments);
    }

    public function testArgumentsInstanceOfJsonSerializable()
    {
        $arguments = new Arguments([]);

        $this->assertInstanceOf(JsonSerializable::class, $arguments);
    }

    public function testArgumentsCreate()
    {
        $arguments = Arguments::create();

        $this->assertInstanceOf(Arguments::class, $arguments);
    }

    public function testArgumentsIsEmpty()
    {
        $this->assertTrue(Arguments::create()->isEmpty());
        $this->assertFalse(Arguments::create(1)->isEmpty());
        $this->assertFalse(Arguments::create(null)->isEmpty());
    }

    public function testArgumentsClear()
    {
        $arguments = Arguments::create(1);

        $this->assertFalse($arguments->isEmpty());
        $this->assertEquals($arguments, $arguments->clear());
        $this->assertTrue($arguments->isEmpty());
    }

    public function testArgumentsArrayable()
    {
        $this->assertEquals([], Arguments::create()->toArray());
        $this->assertEquals([1], Arguments::create(1)->toArray());
        $this->assertEquals([''], Arguments::create(null)->toArray());
        $this->assertEquals([1, '2', 'a'], Arguments::create([1, '2', 'a'])->toArray());
    }

    public function testArgumentsCountable()
    {
        $this->assertEquals(0, count(Arguments::create()));
        $this->assertEquals(1, count(Arguments::create(1)));
        $this->assertEquals(2, count(Arguments::create([1, 2])));
    }

    public function testArgumentsIteratorAggregate()
    {
        $items = [1, '2', 'a'];

        foreach (Arguments::create($items) as $key => $value) {
            $this->assertEquals($items[$key], $value);
        }
    }

    public function testArgumentsJsonSerializable()
    {
        $items = [1, '2', 'a'];

        $this->assertEquals(json_encode($items), json_encode(Arguments::create($items)));
    }

    public function testArgumentsPushOne()
    {
        $arguments = Arguments::create();

        $this->assertEquals([], $arguments->toArray());
        $this->assertEquals($arguments, $arguments->push(1));
        $this->assertEquals([1], $arguments->toArray());

        $arguments->clear()->push('foo');
        $this->assertEquals(['foo'], $arguments->toArray());

        $arguments->clear()->push(null);
        $this->assertEquals([''], $arguments->toArray());

        $arguments->clear()->push(true);
        $this->assertEquals([1], $arguments->toArray());

        $arguments->clear()->push(false);
        $this->assertEquals([0], $arguments->toArray());
    }

    public function testArgumentsPushOneFail()
    {
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('Invalid SQL statement binding parameter.');

        Arguments::create()->push(new stdClass()); // exception
    }

    public function testArgumentsPushMany()
    {
        $arguments = Arguments::create();

        $this->assertEquals([], $arguments->toArray());
        $this->assertEquals($arguments, $arguments->push([1]));
        $this->assertEquals([1], $arguments->toArray());

        $arguments->clear()->push([1, 'foo']);
        $this->assertEquals([1, 'foo'], $arguments->toArray());

        $arguments->clear()->push([null, 1, 'foo']);
        $this->assertEquals(['', 1, 'foo'], $arguments->toArray());

        $arguments->clear()->push([null, 1, 'foo', true, false]);
        $this->assertEquals(['', 1, 'foo', 1, 0], $arguments->toArray());

        $arguments->clear()->push(new Container(['foo' => 'foo', 1]));
        $this->assertEquals(['foo', 1], $arguments->toArray());

        $arguments->clear()->push(Arguments::create(1));
        $this->assertEquals([1], $arguments->toArray());
    }

    public function testArgumentsPushManyFail()
    {
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('Invalid SQL statement binding parameter.');

        Arguments::create()->push([1, new stdClass()]); // exception
    }
}
