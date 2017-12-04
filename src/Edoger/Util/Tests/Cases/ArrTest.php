<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Util\Tests\Cases;

use Edoger\Util\Arr;
use PHPUnit\Framework\TestCase;
use Edoger\Util\Tests\Support\TestArrayable;
use Edoger\Util\Tests\Support\TestIteratorAggregate;

class ArrTest extends TestCase
{
    public function testArrHas()
    {
        $arr = ['foo' => 'foo', 'bar' => null];

        $this->assertTrue(Arr::has($arr, 'foo'));
        $this->assertTrue(Arr::has($arr, 'bar'));
        $this->assertFalse(Arr::has($arr, 'foobar'));
    }

    public function testArrGet()
    {
        $arr = ['foo' => 'foo'];

        $this->assertEquals('foo', Arr::get($arr, 'foo'));
        $this->assertNull(Arr::get($arr, 'bar'));
        $this->assertEquals('bar', Arr::get($arr, 'bar', 'bar'));
    }

    public function testArrFirst()
    {
        $this->assertEquals('foo', Arr::first(['foo' => 'foo', 'bar' => 'bar']));
        $this->assertEquals('bar', Arr::first(['bar', 'foo']));
        $this->assertNull(Arr::first([]));
        $this->assertEquals('bar', Arr::first([], 'bar'));
    }

    public function testArrLast()
    {
        $this->assertEquals('bar', Arr::last(['foo' => 'foo', 'bar' => 'bar']));
        $this->assertEquals('foo', Arr::last(['bar', 'foo']));
        $this->assertNull(Arr::last([]));
        $this->assertEquals('bar', Arr::last([], 'bar'));
    }

    public function testArrWrap()
    {
        $this->assertEquals([], Arr::wrap([]));
        $this->assertEquals(['foo'], Arr::wrap(['foo']));
        $this->assertEquals(['foo'], Arr::wrap('foo'));
        $this->assertEquals(['foo' => 'foo'], Arr::wrap('foo', 'foo'));
        $this->assertEquals([], Arr::wrap(null));
    }

    public function testArrConvert()
    {
        $this->assertEquals([], Arr::convert([]));
        $this->assertEquals(['foo'], Arr::convert(['foo']));
        $this->assertEquals(['foo' => 'foo', 'bar'], Arr::convert(['foo' => 'foo', 'bar']));
        $this->assertEquals(['foo'], Arr::convert(new TestArrayable(['foo'])));
        $this->assertEquals(['foo'], Arr::convert(new TestIteratorAggregate(['foo'])));
        $this->assertEquals(['str'], Arr::convert('str'));
        $this->assertEquals([true], Arr::convert(true));
        $this->assertEquals([false], Arr::convert(false));
        $this->assertEquals([], Arr::convert(null));
    }

    public function testArrKeys()
    {
        $this->assertEquals([], Arr::keys([]));
        $this->assertEquals([0], Arr::keys(['foo']));
        $this->assertEquals(['foo'], Arr::keys(['foo' => 'foo']));
        $this->assertEquals(['foo', 'bar'], Arr::keys(['foo' => 'foo', 'bar' => 'bar']));
    }

    public function testArrValues()
    {
        $this->assertEquals([], Arr::values([]));
        $this->assertEquals(['foo'], Arr::values(['foo']));
        $this->assertEquals(['foo', 'bar'], Arr::values(['foo' => 'foo', 'bar' => 'bar']));
    }

    public function testArrAppend()
    {
        $this->assertEquals([1], Arr::append([], 1));
        $this->assertEquals([1], Arr::append([], [1]));
        $this->assertEquals([1, 2], Arr::append([], [1, 2]));
        $this->assertEquals([1, 1, 2], Arr::append([1], [1, 2]));
        $this->assertEquals([1], Arr::append([1], []));
        $this->assertEquals(['foo'], Arr::append([], 'foo'));
        $this->assertEquals(['foo'], Arr::append([], ['foo']));
        $this->assertEquals([], Arr::append([], []));
        $this->assertEquals([], Arr::append([], null));
        $this->assertEquals([1, 'foo', 'bar'], Arr::append([1], ['foo' => 'foo', 'bar' => 'bar']));
    }

    public function testArrIsAssoc()
    {
        $this->assertTrue(Arr::isAssoc([]));
        $this->assertTrue(Arr::isAssoc(['a' => 1]));
        $this->assertTrue(Arr::isAssoc([2 => 1]));
        $this->assertFalse(Arr::isAssoc([1]));
        $this->assertFalse(Arr::isAssoc([1, 2]));
        $this->assertFalse(Arr::isAssoc([0 => 1, 2]));
    }

    public function testArrMerge()
    {
        $this->assertEquals(['bar'], Arr::merge(['foo'], ['bar']));
        $this->assertEquals(['foo' => 'foo', 'bar'], Arr::merge(['foo' => 'foo'], ['bar']));
        $this->assertEquals(['foo' => 'bar'], Arr::merge(['foo' => 'foo'], ['foo' => 'bar']));
    }
}
