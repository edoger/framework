<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Util\Cases;

use Edoger\Util\Arr;
use PHPUnit\Framework\TestCase;
use EdogerTests\Util\Mocks\TestCallable;
use EdogerTests\Util\Mocks\TestArrayable;
use EdogerTests\Util\Mocks\TestIteratorAggregate;

class ArrTest extends TestCase
{
    public function testArrHas()
    {
        $arr = ['foo' => 'foo', 'bar' => null];

        $this->assertTrue(Arr::has($arr, 'foo'));
        $this->assertTrue(Arr::has($arr, 'bar'));
        $this->assertFalse(Arr::has($arr, 'foobar'));
    }

    public function testArrHasAny()
    {
        $arr = ['foo' => 'foo', 'bar' => null];

        $this->assertTrue(Arr::hasAny($arr, ['foo']));
        $this->assertTrue(Arr::hasAny($arr, ['bar', 'foo']));
        $this->assertTrue(Arr::hasAny($arr, ['baz', 'foo']));
        $this->assertFalse(Arr::hasAny($arr, ['baz']));
        $this->assertFalse(Arr::hasAny($arr, ['non', 'baz']));

        $this->assertTrue(Arr::hasAny($arr, ['baz', 'foo'], $hit1));
        $this->assertEquals('foo', $hit1);

        $this->assertTrue(Arr::hasAny($arr, ['baz', 'bar', 'foo'], $hit2));
        $this->assertEquals('bar', $hit2);

        $this->assertFalse(Arr::hasAny($arr, ['baz', 'non'], $hit3));
        $this->assertNull($hit3);
    }

    public function testArrHasEvery()
    {
        $arr = ['foo' => 'foo', 'bar' => null];

        $this->assertTrue(Arr::hasEvery($arr, ['foo']));
        $this->assertTrue(Arr::hasEvery($arr, ['bar', 'foo']));
        $this->assertTrue(Arr::hasEvery($arr, ['foo', 'bar']));
        $this->assertFalse(Arr::hasEvery($arr, ['baz']));
        $this->assertFalse(Arr::hasEvery($arr, ['foo', 'baz']));
        $this->assertFalse(Arr::hasEvery($arr, ['foo', 'bar', 'baz']));

        $this->assertTrue(Arr::hasEvery($arr, ['bar', 'foo'], $missed1));
        $this->assertNull($missed1);

        $this->assertFalse(Arr::hasEvery($arr, ['foo', 'bar', 'baz'], $missed2));
        $this->assertEquals('baz', $missed2);

        $this->assertFalse(Arr::hasEvery($arr, ['non', 'baz'], $missed3));
        $this->assertEquals('non', $missed3);
    }

    public function testArrGet()
    {
        $arr = ['foo' => 'foo'];

        $this->assertEquals('foo', Arr::get($arr, 'foo'));
        $this->assertNull(Arr::get($arr, 'bar'));
        $this->assertEquals('bar', Arr::get($arr, 'bar', 'bar'));
    }

    public function testArrGetAny()
    {
        $arr = ['foo' => 'foo', 'bar' => null];

        $this->assertEquals('foo', Arr::getAny($arr, ['foo']));
        $this->assertNull(Arr::getAny($arr, ['bar', 'foo']));
        $this->assertEquals('foo', Arr::getAny($arr, ['baz', 'foo']));
        $this->assertNull(Arr::getAny($arr, ['baz']));
        $this->assertNull(Arr::getAny($arr, ['non', 'baz']));
        $this->assertEquals('test', Arr::getAny($arr, ['non', 'baz'], 'test'));

        $this->assertEquals('foo', Arr::getAny($arr, ['baz', 'foo'], null, $hit1));
        $this->assertEquals('foo', $hit1);

        $this->assertNull(Arr::getAny($arr, ['baz', 'bar', 'foo'], 'test', $hit2));
        $this->assertEquals('bar', $hit2);

        $this->assertEquals('test', Arr::getAny($arr, ['baz', 'non'], 'test', $hit3));
        $this->assertNull($hit3);
    }

    public function testArrQuery()
    {
        $arr = [
            'a' => 1,
            'b' => [
                1,
                'm' => 1,
                'n' => null,
                'o' => ['k' => 1],
            ],
            'c' => null,
        ];

        $this->assertEquals($arr['a'], Arr::query($arr, 'a'));
        $this->assertEquals($arr['b'], Arr::query($arr, 'b'));
        $this->assertEquals($arr['c'], Arr::query($arr, 'c'));
        $this->assertEquals($arr['b'][0], Arr::query($arr, 'b.0'));
        $this->assertEquals($arr['b']['m'], Arr::query($arr, 'b.m'));
        $this->assertEquals($arr['b']['n'], Arr::query($arr, 'b.n'));
        $this->assertEquals($arr['b']['o'], Arr::query($arr, 'b.o'));
        $this->assertEquals($arr['b']['o']['k'], Arr::query($arr, 'b.o.k'));
        $this->assertNull(Arr::query($arr, 'non'));
        $this->assertNull(Arr::query($arr, 'b.non'));
        $this->assertNull(Arr::query($arr, 'b.o.non'));
        $this->assertEquals(1, Arr::query($arr, 'non', 1));
        $this->assertEquals(1, Arr::query($arr, 'b.non', 1));
        $this->assertEquals(1, Arr::query($arr, 'b.o.non', 1));
    }

    public function testArrEach()
    {
        $this->assertEquals(
            ['foo' => 'foo'],
            Arr::each(['foo' => 'foo'], function ($v, $k, $p) {
                $this->assertEquals('foo', $v);
                $this->assertEquals('foo', $k);
                $this->assertNull($p);

                return true;
            })
        );

        $this->assertEquals(
            ['foo' => 'foo'],
            Arr::each(['foo' => 'foo'], function ($v, $k, $p) {
                $this->assertEquals('foo', $v);
                $this->assertEquals('foo', $k);
                $this->assertEquals('foo', $p);

                return true;
            }, 'foo')
        );

        $this->assertEquals(
            ['test' => 'foo'],
            Arr::each(['foo' => 'foo'], function ($v, &$k, $p) {
                $k = 'test';

                return true;
            })
        );

        $this->assertEquals(
            ['foo' => 'test'],
            Arr::each(['foo' => 'foo'], function (&$v, $k, $p) {
                $v = 'test';

                return true;
            })
        );

        $this->assertEquals(
            ['test' => 'test'],
            Arr::each(['foo' => 'foo'], function (&$v, &$k, $p) {
                $k = 'test';
                $v = 'test';

                return true;
            })
        );

        $this->assertEquals(
            [],
            Arr::each(['foo' => 'foo'], function ($v, &$k, $p) {
                return false;
            })
        );

        $obj = new TestCallable();

        $this->assertEquals(['test1' => 'test1'], Arr::each(['foo' => 'foo'], [$obj, 'method1']));
        $this->assertEquals(['test2' => 'test2'], Arr::each(['foo' => 'foo'], [TestCallable::class, 'method2']));
        $this->assertEquals(['test2' => 'test2'], Arr::each(['foo' => 'foo'], TestCallable::class.'::method2'));
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
        $this->assertEquals([0], Arr::convert(0));
        $this->assertEquals([''], Arr::convert(''));
        $this->assertEquals(['0'], Arr::convert('0'));
        $this->assertEquals([-5], Arr::convert(-5));

        $f = function () {
            for ($i = 0; $i < 3; ++$i) {
                yield $i;
            }
        };
        $this->assertEquals([0, 1, 2], Arr::convert($f()));

        $o    = new \stdClass();
        $o->a = 1;
        $o->b = 2;
        $this->assertEquals(['a' => 1, 'b' => 2], Arr::convert($o));
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

    public function testArrIsOneDimensional()
    {
        $this->assertTrue(Arr::isOneDimensional([]));
        $this->assertTrue(Arr::isOneDimensional(['foo' => 'foo', 'bar' => 'bar']));
        $this->assertTrue(Arr::isOneDimensional(['foo', 'bar']));
        $this->assertTrue(Arr::isOneDimensional(['foo' => 'foo', 'bar']));
        $this->assertFalse(Arr::isOneDimensional([['foo' => 'foo'], ['foo' => 'bar']]));
        $this->assertFalse(Arr::isOneDimensional([[], []]));
    }

    public function testArrConvertFromXml()
    {
        $this->assertEquals([], Arr::convertFromXml(''));
        $this->assertEquals([], Arr::convertFromXml('<test></test>'));
        $this->assertEquals(['foo'], Arr::convertFromXml('<test>foo</test>'));
        $this->assertEquals(['title' => 'foo'], Arr::convertFromXml('<test><title>foo</title></test>'));
        $this->assertEquals(
            ['element' => ['1', '2']],
            Arr::convertFromXml('<test><element>1</element><element>2</element></test>')
        );

        $this->assertEquals(
            [
                'title'    => 'foo',
                'elements' => [
                    'element' => ['1', '2', '3'],
                    'node'    => '1',
                ],
            ],
            Arr::convertFromXml(
                '<test>
                    <title>foo</title>
                    <elements>
                        <element>1</element>
                        <element>2</element>
                        <element>3</element>
                        <node>1</node>
                    </elements>
                </test>'
            )
        );
    }
}
