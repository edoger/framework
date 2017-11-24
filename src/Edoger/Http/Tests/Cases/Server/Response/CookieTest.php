<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Tests\Cases\Server\Response;

use PHPUnit\Framework\TestCase;
use Edoger\Http\Server\Response\Cookie;

class CookieTest extends TestCase
{
    public function testCookieGetName()
    {
        $cookie = new Cookie('test');

        $this->assertEquals('test', $cookie->getName());
    }

    public function testCookieGetValue()
    {
        $cookie = new Cookie('test');
        $this->assertEquals('', $cookie->getValue());

        $cookie = new Cookie('test', 'test');
        $this->assertEquals('test', $cookie->getValue());
    }

    public function testCookieGetExpiresTime()
    {
        $cookie = new Cookie('test');
        $this->assertEquals(0, $cookie->getExpiresTime());

        $cookie = new Cookie('test', 'test', -86400);
        $this->assertEquals(-86400, $cookie->getExpiresTime());

        $cookie = new Cookie('test', 'test', 86400);
        $this->assertEquals(86400, $cookie->getExpiresTime());
    }

    public function testCookieGetPath()
    {
        $cookie = new Cookie('test');
        $this->assertEquals('/', $cookie->getPath());

        $cookie = new Cookie('test', 'test', 0, '/test');
        $this->assertEquals('/test', $cookie->getPath());
    }

    public function testCookieGetDomain()
    {
        $cookie = new Cookie('test');
        $this->assertEquals('', $cookie->getDomain());

        $cookie = new Cookie('test', 'test', 0, '/', '*.test.com');
        $this->assertEquals('*.test.com', $cookie->getDomain());
    }

    public function testCookieIsSecure()
    {
        $cookie = new Cookie('test');
        $this->assertFalse($cookie->isSecure());

        $cookie = new Cookie('test', 'test', 0, '/', '', true);
        $this->assertTrue($cookie->isSecure());
    }

    public function testCookieIsHttpOnly()
    {
        $cookie = new Cookie('test');
        $this->assertFalse($cookie->isHttpOnly());

        $cookie = new Cookie('test', 'test', 0, '/', '', false, true);
        $this->assertTrue($cookie->isHttpOnly());
    }
}
