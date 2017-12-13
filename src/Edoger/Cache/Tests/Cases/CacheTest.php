<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Cache\Tests\Cases\Drivers;

use Edoger\Cache\Cache;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Edoger\Cache\Contracts\Driver;
use Edoger\Cache\Tests\Support\TestDriver;

class CacheTest extends TestCase
{
    public function testCacheGetDriver()
    {
        $driver = new TestDriver();
        $cache  = new Cache($driver);

        $this->assertEquals($driver, $cache->getDriver());
        $this->assertInstanceOf(Driver::class, $driver);
    }

    public function testCacheGetPrefix()
    {
        $driver = new TestDriver();
        $cache  = new Cache($driver);
        $this->assertEquals('edoger::cache::', $cache->getPrefix());
        $cache = new Cache($driver, 'test');
        $this->assertEquals('test', $cache->getPrefix());
    }

    public function testCacheSetPrefix()
    {
        $driver = new TestDriver();
        $cache  = new Cache($driver);

        $this->assertEquals('edoger::cache::', $cache->getPrefix());
        $cache->setPrefix('test');
        $this->assertEquals('test', $cache->getPrefix());
    }

    public function testCacheHas()
    {
        $driver = new TestDriver();
        $cache  = new Cache($driver, '');

        $this->assertTrue($cache->has('foo'));
        $this->assertTrue($cache->has('bar'));
        $this->assertTrue($cache->has('baz'));
        $this->assertFalse($cache->has('non'));
    }

    public function testCacheHasAny()
    {
        $driver = new TestDriver();
        $cache  = new Cache($driver, '');

        $this->assertTrue($cache->hasAny(['foo', 'non']));
        $this->assertTrue($cache->hasAny(['bar', 'foo', 'baz']));
        $this->assertTrue($cache->hasAny(['non', 'none', 'baz']));
        $this->assertFalse($cache->hasAny(['non', 'none']));
    }

    public function testCacheHasAnyFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The cache key must be a string.');

        $driver = new TestDriver();
        $cache  = new Cache($driver, '');

        $cache->hasAny([[]]); // exception
    }

    public function testCacheGet()
    {
        $driver = new TestDriver();
        $cache  = new Cache($driver, '');

        $this->assertEquals('foo', $cache->get('foo'));
        $this->assertEquals('bar', $cache->get('bar'));
        $this->assertEquals('baz', $cache->get('baz'));
        $this->assertNull($cache->get('non'));
        $this->assertEquals('non', $cache->get('non', 'non'));
    }

    public function testCacheGetAny()
    {
        $driver = new TestDriver();
        $cache  = new Cache($driver, '');

        $this->assertEquals('foo', $cache->getAny(['foo', 'non']));
        $this->assertEquals('bar', $cache->getAny(['bar', 'foo', 'baz']));
        $this->assertEquals('baz', $cache->getAny(['non', 'none', 'baz']));
        $this->assertNull($cache->getAny(['non', 'none']));
        $this->assertEquals('non', $cache->getAny(['non', 'none'], 'non'));
    }

    public function testCacheGetAnyFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The cache key must be a string.');

        $driver = new TestDriver();
        $cache  = new Cache($driver, '');

        $cache->getAny([[]]); // exception
    }

    public function testCacheGetMultiple()
    {
        $driver = new TestDriver();
        $cache  = new Cache($driver, '');

        $this->assertEquals(
            ['foo' => 'foo', 'non' => null, 'none' => null],
            $cache->getMultiple(['foo', 'non', 'none'])
         );
        $this->assertEquals(
            ['foo' => 'foo', 'non' => 'non', 'none' => 'non'],
            $cache->getMultiple(['foo', 'non', 'none'], 'non')
        );
    }

    public function testCacheGetMultipleFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The cache key must be a string.');

        $driver = new TestDriver();
        $cache  = new Cache($driver, '');

        $cache->getMultiple([[]]); // exception
    }

    public function testCacheSet()
    {
        $driver = new TestDriver(['bar' => 'bar']);
        $cache  = new Cache($driver, '');

        $this->assertFalse($cache->has('foo'));
        $this->assertTrue($cache->set('foo', 'foo'));
        $this->assertFalse($cache->set('foo', 'bar'));
        $this->assertFalse($cache->set('bar', 'foo'));
    }

    public function testCacheSetMultiple()
    {
        $driver = new TestDriver(['bar' => 'bar']);
        $cache  = new Cache($driver, '');

        $this->assertTrue($cache->setMultiple(['foo' => 'foo', 'baz' => 'baz']));
        $this->assertFalse($cache->setMultiple(['foo' => 'bar']));
        $this->assertFalse($cache->setMultiple(['bar' => 'foo', 'non' => 'non'], 0, $failed));

        $this->assertEquals(['bar' => 'foo'], $failed);
    }

    public function testCacheDelete()
    {
        $driver = new TestDriver(['bar' => 'bar']);
        $cache  = new Cache($driver, '');

        $this->assertTrue($cache->has('bar'));
        $this->assertTrue($cache->delete('bar'));
        $this->assertFalse($cache->has('bar'));
        $this->assertFalse($cache->delete('bar'));
        $this->assertFalse($cache->has('bar'));
    }

    public function testCacheDeleteMultiple()
    {
        $driver = new TestDriver();
        $cache  = new Cache($driver, '');

        $this->assertTrue($cache->has('foo'));
        $this->assertTrue($cache->has('bar'));
        $this->assertTrue($cache->has('baz'));
        $this->assertFalse($cache->has('non'));

        $this->assertTrue($cache->deleteMultiple(['foo', 'bar']));
        $this->assertFalse($cache->deleteMultiple(['foo', 'bar']));
        $this->assertFalse($cache->deleteMultiple(['bar', 'baz', 'non'], $failed));

        $this->assertEquals(['bar', 'non'], $failed);
    }

    public function testCacheDeleteMultipleFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The cache key must be a string.');

        $driver = new TestDriver();
        $cache  = new Cache($driver, '');

        $cache->deleteMultiple([[]]); //exception
    }

    public function testCacheClear()
    {
        $driver = new TestDriver(['bar' => 'bar', 'baz' => 'baz']);
        $cache  = new Cache($driver, '');

        $this->assertTrue($cache->has('bar'));
        $this->assertTrue($cache->has('baz'));

        $this->assertTrue($cache->clear());

        $this->assertFalse($cache->has('bar'));
        $this->assertFalse($cache->has('baz'));

        $this->assertFalse($cache->clear());
    }
}
