<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Session\Cases\Stores;

use PHPUnit\Framework\TestCase;
use Edoger\Session\Contracts\SessionStore;
use Edoger\Session\Stores\GlobalSessionStore;

class GlobalSessionStoreTest extends TestCase
{
    protected $store;

    protected function setUp()
    {
        $this->store = new GlobalSessionStore();
        $_SESSION    = ['foo' => 'foo'];
    }

    protected function tearDown()
    {
        $this->store = null;
        $this->unsetGlobal();
    }

    protected function unsetGlobal()
    {
        unset($_SESSION);
    }

    public function testGlobalSessionStoreInstanceOfSessionStore()
    {
        $this->assertInstanceOf(SessionStore::class, $this->store);
    }

    public function testGlobalSessionStoreIsEmpty()
    {
        $this->assertFalse($this->store->isEmpty());
        $this->unsetGlobal();
        $this->assertTrue($this->store->isEmpty());
    }

    public function testGlobalSessionStoreHas()
    {
        $this->assertTrue($this->store->has('foo'));
        $this->assertFalse($this->store->has('bar'));
    }

    public function testGlobalSessionStoreGet()
    {
        $this->assertEquals('foo', $this->store->get('foo'));
        $this->assertNull($this->store->get('bar'));
        $this->assertEquals('bar', $this->store->get('bar', 'bar'));
    }

    public function testGlobalSessionStoreGetAll()
    {
        $this->assertEquals(['foo' => 'foo'], $this->store->getAll());
        $this->unsetGlobal();
        $this->assertEquals([], $this->store->getAll());
    }

    public function testGlobalSessionStoreSet()
    {
        $this->assertEquals('foo', $this->store->get('foo'));
        $this->store->set('foo', 'test');
        $this->assertEquals('test', $this->store->get('foo'));
    }

    public function testGlobalSessionStoreDelete()
    {
        $this->assertTrue($this->store->has('foo'));
        $this->store->delete('foo');
        $this->assertFalse($this->store->has('foo'));
    }

    public function testGlobalSessionStoreClear()
    {
        $this->store->set('bar', 'bar');

        $this->assertEquals(['foo' => 'foo', 'bar' => 'bar'], $this->store->getAll());
        $this->store->clear();
        $this->assertEquals([], $this->store->getAll());
        $this->assertTrue($this->store->isEmpty());
    }
}
