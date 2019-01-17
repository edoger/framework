<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Session\Cases\Stores;

use PHPUnit\Framework\TestCase;
use Edoger\Session\Contracts\SessionStore;
use Edoger\Session\Stores\ArraySessionStore;

class ArraySessionStoreTest extends TestCase
{
    protected $store;

    protected function setUp()
    {
        $this->store = new ArraySessionStore(['foo' => 'foo']);
    }

    protected function tearDown()
    {
        $this->store = null;
    }

    public function testArraySessionStoreInstanceOfSessionStore()
    {
        $this->assertInstanceOf(SessionStore::class, $this->store);
    }

    public function testArraySessionStoreIsEmpty()
    {
        $store = new ArraySessionStore();

        $this->assertTrue($store->isEmpty());
        $this->assertFalse($this->store->isEmpty());
    }

    public function testArraySessionStoreHas()
    {
        $this->assertTrue($this->store->has('foo'));
        $this->assertFalse($this->store->has('bar'));
    }

    public function testArraySessionStoreGet()
    {
        $this->assertEquals('foo', $this->store->get('foo'));
        $this->assertNull($this->store->get('bar'));
        $this->assertEquals('bar', $this->store->get('bar', 'bar'));
    }

    public function testArraySessionStoreGetAll()
    {
        $store = new ArraySessionStore();

        $this->assertEquals([], $store->getAll());
        $this->assertEquals(['foo' => 'foo'], $this->store->getAll());
    }

    public function testArraySessionStoreSet()
    {
        $this->assertEquals('foo', $this->store->get('foo'));
        $this->store->set('foo', 'test');
        $this->assertEquals('test', $this->store->get('foo'));
    }

    public function testArraySessionStoreDelete()
    {
        $this->assertTrue($this->store->has('foo'));
        $this->store->delete('foo');
        $this->assertFalse($this->store->has('foo'));
    }

    public function testArraySessionStoreClear()
    {
        $this->store->set('bar', 'bar');

        $this->assertEquals(['foo' => 'foo', 'bar' => 'bar'], $this->store->getAll());
        $this->store->clear();
        $this->assertEquals([], $this->store->getAll());
        $this->assertTrue($this->store->isEmpty());
    }
}
