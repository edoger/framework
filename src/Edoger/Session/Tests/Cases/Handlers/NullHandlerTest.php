<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Session\Tests\Cases\Handlers;

use SessionHandlerInterface;
use PHPUnit\Framework\TestCase;
use Edoger\Session\Handlers\NullHandler;
use Edoger\Session\Contracts\SessionHandler;

class NullHandlerTest extends TestCase
{
    protected $handler;

    protected function setUp()
    {
        $this->handler = new NullHandler();
    }

    protected function tearDown()
    {
        $this->handler = null;
    }

    public function testNullHandlerInstanceOfSessionHandlerInterface()
    {
        $this->assertInstanceOf(SessionHandlerInterface::class, $this->handler);
    }

    public function testNullHandlerInstanceOfSessionHandler()
    {
        $this->assertInstanceOf(SessionHandler::class, $this->handler);
    }

    public function testNullHandlerClose()
    {
        $this->assertTrue($this->handler->close());
    }

    public function testNullHandlerDestroy()
    {
        $this->assertTrue($this->handler->destroy('test'));
    }

    public function testNullHandlerGc()
    {
        $this->assertTrue($this->handler->gc(1));
    }

    public function testNullHandlerOpen()
    {
        $this->assertTrue($this->handler->open('/tmp', 'test'));
    }

    public function testNullHandlerRead()
    {
        $this->assertEquals('', $this->handler->read('test'));
    }

    public function testNullHandlerWrite()
    {
        $this->assertTrue($this->handler->write('test', 'test'));
    }
}
