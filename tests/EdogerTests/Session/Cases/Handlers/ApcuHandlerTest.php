<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Session\Cases\Handlers;

use SessionHandlerInterface;
use PHPUnit\Framework\TestCase;
use Edoger\Cache\Drivers\ApcuDriver;
use Edoger\Session\Handlers\ApcuHandler;
use Edoger\Session\Contracts\SessionHandler;

class ApcuHandlerTest extends TestCase
{
    protected static $supported;
    protected $driver;

    public static function setUpBeforeClass()
    {
        self::$supported = ApcuDriver::isEnabled();
    }

    protected function setUp()
    {
        if (self::$supported) {
            $this->initTestCacheData();
            $this->driver = new ApcuDriver();
        } else {
            $this->markTestSkipped('The "apcu" extension is not loaded or not enabled.');
        }
    }

    protected function tearDown()
    {
        if (self::$supported) {
            $this->driver = null;
        }

        $this->clearTestCacheData();
    }

    protected function initTestCacheData()
    {
        if (self::$supported) {
            apcu_store('edoger::session::testsid', 'test');
            apcu_store('test::testsid', 'test');
        }
    }

    protected function clearTestCacheData()
    {
        if (self::$supported) {
            apcu_clear_cache();
        }
    }

    public function testApcuHandlerInstanceOfSessionHandlerInterface()
    {
        $handler = new ApcuHandler($this->driver);

        $this->assertInstanceOf(SessionHandlerInterface::class, $handler);
    }

    public function testApcuHandlerInstanceOfSessionHandler()
    {
        $handler = new ApcuHandler($this->driver);

        $this->assertInstanceOf(SessionHandler::class, $handler);
    }

    public function testApcuHandlerClose()
    {
        $handler = new ApcuHandler($this->driver);

        $this->assertTrue($handler->close());
    }

    public function testApcuHandlerDestroy()
    {
        $handler = new ApcuHandler($this->driver);

        $this->assertEquals('test', $this->driver->get('edoger::session::testsid'));
        $this->assertTrue($handler->destroy('testsid'));
        $this->assertNull($this->driver->get('edoger::session::testsid'));

        $handler = new ApcuHandler($this->driver, 7200, 'test::');

        $this->assertEquals('test', $this->driver->get('test::testsid'));
        $this->assertTrue($handler->destroy('testsid'));
        $this->assertNull($this->driver->get('test::testsid'));
    }

    public function testApcuHandlerGc()
    {
        $handler = new ApcuHandler($this->driver);

        $this->assertTrue($handler->gc(1));
    }

    public function testApcuHandlerOpen()
    {
        $handler = new ApcuHandler($this->driver);

        $this->assertTrue($handler->open('/tmp', 'test'));
    }

    public function testApcuHandlerRead()
    {
        $handler = new ApcuHandler($this->driver);

        $this->assertEquals($this->driver->get('edoger::session::testsid'), $handler->read('testsid'));

        $handler = new ApcuHandler($this->driver, 7200, 'test::');

        $this->assertEquals($this->driver->get('test::testsid'), $handler->read('testsid'));
    }

    public function testApcuHandlerWrite()
    {
        $handler = new ApcuHandler($this->driver);

        $this->assertTrue($handler->write('testsid', 'foo'));
        $this->assertEquals('foo', $this->driver->get('edoger::session::testsid'));

        $handler = new ApcuHandler($this->driver, 7200, 'test::');

        $this->assertTrue($handler->write('testsid', 'foo'));
        $this->assertEquals('foo', $this->driver->get('test::testsid'));
    }
}
