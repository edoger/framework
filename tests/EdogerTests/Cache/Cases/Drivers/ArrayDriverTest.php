<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Cache\Cases\Drivers;

use PHPUnit\Framework\TestCase;
use Edoger\Cache\Contracts\Driver;
use Edoger\Cache\Drivers\ArrayDriver;

class ArrayDriverTest extends TestCase
{
    protected $driver;

    protected function setUp()
    {
        $this->driver = new ArrayDriver(['foo' => 'foo']);
    }

    protected function tearDown()
    {
        $this->driver = null;
    }

    public function testArrayDriverIsEnabled()
    {
        $this->assertTrue(ArrayDriver::isEnabled());
    }

    public function testArrayDriverInstanceOfDriver()
    {
        $this->assertInstanceOf(Driver::class, $this->driver);
    }

    public function testArrayDriverHas()
    {
        $this->assertTrue($this->driver->has('foo'));
        $this->assertFalse($this->driver->has('bar'));
    }

    public function testArrayDriverGet()
    {
        $this->assertEquals('foo', $this->driver->get('foo'));
        $this->assertNull($this->driver->get('bar'));
        $this->assertEquals('bar', $this->driver->get('bar', 'bar'));
    }

    public function testArrayDriverSet()
    {
        $this->assertEquals('foo', $this->driver->get('foo'));
        $this->assertTrue($this->driver->set('foo', 'test'));
        $this->assertEquals('test', $this->driver->get('foo'));
    }

    public function testArrayDriverDelete()
    {
        $this->assertTrue($this->driver->has('foo'));
        $this->assertTrue($this->driver->delete('foo'));
        $this->assertFalse($this->driver->has('foo'));

        $this->assertFalse($this->driver->has('bar'));
        $this->assertTrue($this->driver->delete('bar'));
        $this->assertFalse($this->driver->has('bar'));
    }

    public function testArrayDriverClear()
    {
        $this->driver->set('bar', 'bar');

        $this->assertTrue($this->driver->has('foo'));
        $this->assertTrue($this->driver->has('bar'));
        $this->assertFalse($this->driver->has('baz'));

        $this->assertTrue($this->driver->clear());

        $this->assertFalse($this->driver->has('foo'));
        $this->assertFalse($this->driver->has('bar'));
        $this->assertFalse($this->driver->has('baz'));
    }
}
