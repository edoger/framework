<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Cache\Tests\Cases\Drivers;

use RuntimeException;
use PHPUnit\Framework\TestCase;
use Edoger\Cache\Contracts\Driver;
use Edoger\Cache\Drivers\ApcuDriver;
use Edoger\Cache\Tests\Support\DisabledApcuDriver;

class ApcuDriverTest extends TestCase
{
    protected static $supported;

    public static function setUpBeforeClass()
    {
        self::$supported = extension_loaded('apcu') && ini_get('apc.enabled');

        if (self::$supported && 'cli' === PHP_SAPI) {
            self::$supported = ini_get('apc.enable_cli');
        }
    }

    protected function setUp()
    {
        if (self::$supported) {
            $this->initTestCacheData();
        } else {
            $this->markTestSkipped('The "apcu" extension is not loaded or not enabled.');
        }
    }

    protected function tearDown()
    {
        $this->clearTestCacheData();
    }

    protected function initTestCacheData()
    {
        if (self::$supported) {
            apcu_store('edoger_test_a', 'test_a');
            apcu_store('edoger_test_b', 'test_b');
            apcu_store('edoger_test_c', 'test_c');
        }
    }

    protected function clearTestCacheData()
    {
        if (self::$supported) {
            apcu_clear_cache();
        }
    }

    public function testApcuDriverConstructorFail()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The "apcu" extension is not loaded or not enabled.');

        new DisabledApcuDriver(); // exception
    }

    public function testApcuDriverIsEnabled()
    {
        if (self::$supported) {
            $this->assertTrue(ApcuDriver::isEnabled());
        } else {
            $this->assertFalse(ApcuDriver::isEnabled());
        }
    }

    public function testApcuDriverInstanceOfDriver()
    {
        $driver = new ApcuDriver();

        $this->assertInstanceOf(Driver::class, $driver);
    }

    public function testApcuDriverHas()
    {
        $driver = new ApcuDriver();

        $this->assertTrue($driver->has('edoger_test_a'));
        $this->assertTrue($driver->has('edoger_test_b'));
        $this->assertTrue($driver->has('edoger_test_c'));
        $this->assertFalse($driver->has('edoger_test_d'));
    }

    public function testApcuDriverGet()
    {
        $driver = new ApcuDriver();

        $this->assertEquals('test_a', $driver->get('edoger_test_a'));
        $this->assertEquals('test_b', $driver->get('edoger_test_b'));
        $this->assertEquals('test_c', $driver->get('edoger_test_c'));
        $this->assertNull($driver->get('edoger_test_d'));
        $this->assertEquals('test', $driver->get('edoger_test_d', 'test'));
    }

    public function testApcuDriverSet()
    {
        $driver = new ApcuDriver();

        $this->assertEquals('test_a', $driver->get('edoger_test_a'));
        $this->assertTrue($driver->set('edoger_test_a', 'test'));
        $this->assertEquals('test', $driver->get('edoger_test_a'));
    }

    public function testApcuDriverDelete()
    {
        $driver = new ApcuDriver();

        $this->assertTrue($driver->has('edoger_test_a'));
        $this->assertTrue($driver->delete('edoger_test_a'));
        $this->assertFalse($driver->has('edoger_test_a'));

        $this->assertFalse($driver->has('edoger_test_d'));
        $this->assertTrue($driver->delete('edoger_test_d'));
        $this->assertFalse($driver->has('edoger_test_d'));
    }

    public function testApcuDriverClear()
    {
        $driver = new ApcuDriver();

        $this->assertTrue($driver->has('edoger_test_a'));
        $this->assertTrue($driver->has('edoger_test_b'));
        $this->assertTrue($driver->has('edoger_test_c'));
        $this->assertFalse($driver->has('edoger_test_d'));

        $this->assertTrue($driver->clear());

        $this->assertFalse($driver->has('edoger_test_a'));
        $this->assertFalse($driver->has('edoger_test_b'));
        $this->assertFalse($driver->has('edoger_test_c'));
        $this->assertFalse($driver->has('edoger_test_d'));
    }
}
