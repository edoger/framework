<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Container\Cases;

use Countable;
use IteratorAggregate;
use Edoger\Container\Store;
use PHPUnit\Framework\TestCase;
use Edoger\Util\Contracts\Arrayable;

class StoreTest extends TestCase
{
    public function testStoreInstanceOfArrayable()
    {
        $this->assertInstanceOf(Arrayable::class, new Store());
    }

    public function testStoreInstanceOfCountable()
    {
        $this->assertInstanceOf(Countable::class, new Store());
    }

    public function testStoreInstanceOfIteratorAggregate()
    {
        $this->assertInstanceOf(IteratorAggregate::class, new Store());
    }

    public function testStoreIsEmpty()
    {
        $store = new Store();
        $this->assertTrue($store->isEmpty());

        $store = new Store(1);
        $this->assertFalse($store->isEmpty());
    }

    public function testStoreAppend()
    {
        $store = new Store();

        $this->assertEquals(1, $store->append(1));
        $this->assertEquals(2, $store->append(1));
        $this->assertEquals(3, $store->append(1));

        $this->assertEquals(4, $store->append(1, true));
        $this->assertEquals(5, $store->append(1, true));
        $this->assertEquals(6, $store->append(1, true));
    }

    public function testStoreRemove()
    {
        $store = new Store([1, 2, 3, 4, 5, 6]);

        $this->assertEquals(1, $store->remove());
        $this->assertEquals(2, $store->remove());
        $this->assertEquals(3, $store->remove());
        $this->assertEquals(6, $store->remove(false));
        $this->assertEquals(5, $store->remove(false));
        $this->assertEquals(4, $store->remove(false));

        $this->assertNull($store->remove());
        $this->assertNull($store->remove(false));
    }

    public function testStoreClear()
    {
        $store = new Store(1);

        $this->assertFalse($store->isEmpty());
        $this->assertEquals($store, $store->clear());
        $this->assertTrue($store->isEmpty());
    }

    public function testStoreArrayable()
    {
        $store = new Store();
        $this->assertEquals([], $store->toArray());

        $store = new Store(1);
        $this->assertEquals([1], $store->toArray());

        $store = new Store([1]);
        $this->assertEquals([1], $store->toArray());

        $store = new Store([1, 2]);
        $this->assertEquals([1, 2], $store->toArray());

        $store = new Store([1, 2, 3, 4]);
        $this->assertEquals([1, 2, 3, 4], $store->toArray());

        $store = new Store(['foo' => 'foo']);
        $this->assertEquals(['foo' => 'foo'], $store->toArray());
    }

    public function testStoreCountable()
    {
        $this->assertEquals(0, count(new Store()));
        $this->assertEquals(1, count(new Store(['foo'])));
        $this->assertEquals(1, count(new Store(['foo' => 'foo'])));
    }

    public function testStoreIteratorAggregate()
    {
        $elements = ['foo' => 'foo', 'bar'];
        $store    = new Store($elements);

        foreach ($store as $key => $value) {
            $this->assertEquals($elements[$key], $value);
        }
    }
}
