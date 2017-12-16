<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Session\Tests\Cases;

use Countable;
use IteratorAggregate;
use PHPUnit\Framework\TestCase;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Session\Contracts\SessionStore;
use Edoger\Session\Contracts\SessionHandler;
use Edoger\Session\Tests\Support\TestSession;
use Edoger\Session\Tests\Support\TestSessionStore;
use Edoger\Session\Tests\Support\TestSessionHandler;

class AbstractSessionTest extends TestCase
{
    protected $storeData   = [];
    protected $handlerData = [];

    protected function setUp()
    {
        $this->storeData   = ['test' => 'test'];
        $this->handlerData = ['test' => 'test'];
    }

    protected function tearDown()
    {
        $this->clearTestData();
    }

    protected function clearTestData()
    {
        $this->storeData   = [];
        $this->handlerData = [];

        return $this;
    }

    protected function createTestSession()
    {
        return new TestSession(
            new TestSessionStore($this->storeData),
            new TestSessionHandler($this->handlerData)
        );
    }

    public function testAbstractSessionInstanceOfCountable()
    {
        $this->assertInstanceOf(Countable::class, $this->createTestSession());
    }

    public function testAbstractSessionInstanceOfArrayable()
    {
        $this->assertInstanceOf(Arrayable::class, $this->createTestSession());
    }

    public function testAbstractSessionInstanceOfIteratorAggregate()
    {
        $this->assertInstanceOf(IteratorAggregate::class, $this->createTestSession());
    }

    public function testAbstractSessionGetSessionId()
    {
        $session = $this->createTestSession();

        $this->assertEquals('', $session->getSessionId());
        $session->start('foo');
        $this->assertEquals('foo', $session->getSessionId());
        $session->start('bar');
        $this->assertEquals('bar', $session->getSessionId());
    }

    public function testAbstractSessionIsStarted()
    {
        $session = $this->createTestSession();

        $this->assertFalse($session->isStarted());
        $session->start('foo');
        $this->assertTrue($session->isStarted());
    }

    public function testAbstractSessionGetSessionHandler()
    {
        $this->assertInstanceOf(SessionHandler::class, $this->createTestSession()->getSessionHandler());

        $handler = new TestSessionHandler();
        $session = new TestSession(new TestSessionStore(), $handler);

        $this->assertInstanceOf(SessionHandler::class, $session->getSessionHandler());
        $this->assertEquals($handler, $session->getSessionHandler());
    }

    public function testAbstractSessionGetSessionStore()
    {
        $this->assertInstanceOf(SessionStore::class, $this->createTestSession()->getSessionStore());

        $store   = new TestSessionStore();
        $session = new TestSession($store, new TestSessionHandler());

        $this->assertInstanceOf(SessionStore::class, $session->getSessionStore());
        $this->assertEquals($store, $session->getSessionStore());
    }

    public function testAbstractSessionSetSessionStore()
    {
        $session = $this->createTestSession();

        $this->assertEquals($this->storeData, $session->getSessionStore()->getData());

        $store = new TestSessionStore();
        $session->setSessionStore($store);
        $this->assertEquals([], $session->getSessionStore()->getData());

        $store = new TestSessionStore(['foo' => 'foo']);
        $session->setSessionStore($store);
        $this->assertEquals(['foo' => 'foo'], $session->getSessionStore()->getData());
    }

    public function testAbstractSessionIsEmpty()
    {
        $session = $this->createTestSession();

        $this->assertFalse($session->isEmpty());

        $store = new TestSessionStore();
        $session->setSessionStore($store);
        $this->assertTrue($session->isEmpty());

        $store = new TestSessionStore(['foo' => 'foo']);
        $session->setSessionStore($store);
        $this->assertFalse($session->isEmpty());
    }

    public function testAbstractSessionHas()
    {
        $session = $this->createTestSession();

        $this->assertTrue($session->has('test'));
        $this->assertFalse($session->has('foo'));
    }

    public function testAbstractSessionGet()
    {
        $session = $this->createTestSession();

        $this->assertEquals('test', $session->get('test'));
        $this->assertNull($session->get('foo'));
        $this->assertEquals('foo', $session->get('foo', 'foo'));
    }

    public function testAbstractSessionGetAll()
    {
        $session = $this->createTestSession();

        $this->assertEquals(['test' => 'test'], $session->getAll());

        $store = new TestSessionStore();
        $session->setSessionStore($store);
        $this->assertEquals([], $session->getAll());

        $store = new TestSessionStore(['foo' => 'foo', 'bar' => 'bar']);
        $session->setSessionStore($store);
        $this->assertEquals(['foo' => 'foo', 'bar' => 'bar'], $session->getAll());
    }

    public function testAbstractSessionSet()
    {
        $session = $this->createTestSession();

        $this->assertEquals('test', $session->get('test'));
        $session->set('test', 'foo');
        $this->assertEquals('foo', $session->get('test'));
    }

    public function testAbstractSessionDelete()
    {
        $session = $this->createTestSession();

        $this->assertTrue($session->has('test'));
        $this->assertEquals($session, $session->delete('test'));
        $this->assertFalse($session->has('test'));
        $this->assertFalse($session->has('foo'));
        $this->assertEquals($session, $session->delete('foo'));
        $this->assertFalse($session->has('foo'));
    }

    public function testAbstractSessionClear()
    {
        $session = $this->createTestSession();
        $store   = new TestSessionStore(['foo' => 'foo', 'bar' => 'bar']);
        $session->setSessionStore($store);

        $this->assertEquals(['foo' => 'foo', 'bar' => 'bar'], $session->getAll());
        $this->assertFalse($session->isEmpty());
        $this->assertEquals($session, $session->clear());
        $this->assertEquals([], $session->getAll());
        $this->assertTrue($session->isEmpty());
    }

    public function testAbstractSessionCountable()
    {
        $session = $this->createTestSession();

        $this->assertEquals(1, count($session));

        $store = new TestSessionStore();
        $session->setSessionStore($store);
        $this->assertEquals(0, count($session));

        $store = new TestSessionStore(['foo' => 'foo', 'bar' => 'bar']);
        $session->setSessionStore($store);
        $this->assertEquals(2, count($session));
    }

    public function testAbstractSessionArrayable()
    {
        $session = $this->createTestSession();

        $this->assertEquals(['test' => 'test'], $session->toArray());

        $store = new TestSessionStore();
        $session->setSessionStore($store);
        $this->assertEquals([], $session->toArray());

        $store = new TestSessionStore(['foo' => 'foo', 'bar' => 'bar']);
        $session->setSessionStore($store);
        $this->assertEquals(['foo' => 'foo', 'bar' => 'bar'], $session->toArray());
    }

    public function testAbstractSessionIteratorAggregate()
    {
        $data    = ['foo' => 'foo', 'bar' => 'bar'];
        $session = $this->createTestSession();
        $store   = new TestSessionStore($data);
        $session->setSessionStore($store);

        foreach ($session as $key => $value) {
            $this->assertArrayHasKey($key, $data);
            $this->assertEquals($data[$key], $value);
        }
    }
}
