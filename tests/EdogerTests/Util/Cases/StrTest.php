<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Util\Cases;

use Edoger\Util\Str;
use PHPUnit\Framework\TestCase;

class StrTest extends TestCase
{
    public function testStrLength()
    {
        $this->assertEquals(0, Str::length(''));
        $this->assertEquals(4, Str::length('程序测试'));
        $this->assertEquals(3, Str::length('foo'));
    }

    public function testStrWidth()
    {
        $this->assertEquals(0, Str::width(''));
        $this->assertEquals(8, Str::width('程序测试'));
        $this->assertEquals(3, Str::width('foo'));
        $this->assertEquals(7, Str::width('foo测试'));
    }

    public function testStrUpper()
    {
        $this->assertEquals('', Str::upper(''));
        $this->assertEquals('1', Str::upper('1'));
        $this->assertEquals('FOO', Str::upper('foo'));
        $this->assertEquals('BAR', Str::upper('BAR'));
        $this->assertEquals('程序测试', Str::upper('程序测试'));
    }

    public function testStrLower()
    {
        $this->assertEquals('', Str::lower(''));
        $this->assertEquals('1', Str::lower('1'));
        $this->assertEquals('foo', Str::lower('foo'));
        $this->assertEquals('bar', Str::lower('BAR'));
        $this->assertEquals('程序测试', Str::lower('程序测试'));
    }

    public function testStrSubstr()
    {
        $this->assertEquals('bar', Str::substr('bar', 0));
        $this->assertEquals('b', Str::substr('bar', 0, 1));
        $this->assertEquals('r', Str::substr('bar', -1));
        $this->assertEquals('a', Str::substr('bar', -2, 1));
        $this->assertEquals('', Str::substr('bar', 5));
        $this->assertEquals('ar', Str::substr('bar', 1, 5));
    }

    public function testStrBefore()
    {
        $this->assertEquals('', Str::before('bar', 0));
        $this->assertEquals('b', Str::before('bar', 1));
        $this->assertEquals('ba', Str::before('bar', 2));
        $this->assertEquals('bar', Str::before('bar', 5));
    }

    public function testStrAfter()
    {
        $this->assertEquals('', Str::after('bar', 0));
        $this->assertEquals('r', Str::after('bar', 1));
        $this->assertEquals('ar', Str::after('bar', 2));
        $this->assertEquals('bar', Str::after('bar', 5));
    }

    public function testStrUcfirst()
    {
        $this->assertEquals('', Str::ucfirst(''));
        $this->assertEquals('1', Str::ucfirst('1'));
        $this->assertEquals('A', Str::ucfirst('a'));
        $this->assertEquals('FOO', Str::ucfirst('FOO'));
        $this->assertEquals('Foo', Str::ucfirst('foo'));
    }

    public function testStrLcfirst()
    {
        $this->assertEquals('', Str::lcfirst(''));
        $this->assertEquals('1', Str::lcfirst('1'));
        $this->assertEquals('a', Str::lcfirst('A'));
        $this->assertEquals('fOO', Str::lcfirst('FOO'));
        $this->assertEquals('foo', Str::lcfirst('foo'));
    }

    public function testStrStrpos()
    {
        $this->assertEquals(0, Str::strpos('Hello World', 'Hello'));
        $this->assertEquals(6, Str::strpos('Hello World', 'World'));
        $this->assertEquals(4, Str::strpos('Hello World', 'o'));
        $this->assertEquals(7, Str::strpos('Hello World', 'o', 5));
        $this->assertFalse(Str::strpos('Hello World', 'a'));
        $this->assertFalse(Str::strpos('Hello World', 'o', 8));
    }
}
