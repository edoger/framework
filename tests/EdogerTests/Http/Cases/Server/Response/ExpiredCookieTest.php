<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Http\Cases\Server\Response;

use PHPUnit\Framework\TestCase;
use Edoger\Http\Server\Response\Cookie;
use Edoger\Http\Server\Response\ExpiredCookie;

class ExpiredCookieTest extends TestCase
{
    public function testExpiredCookieExtendsCookie()
    {
        $cookie = new ExpiredCookie('test');

        $this->assertInstanceOf(Cookie::class, $cookie);
    }

    public function testExpiredCookieConstructor()
    {
        $cookie = new ExpiredCookie('test');

        $this->assertEquals('test', $cookie->getName());
        $this->assertEquals('', $cookie->getValue());
        $this->assertEquals(-31536000, $cookie->getExpiresTime());
        $this->assertEquals('/', $cookie->getPath());
        $this->assertEquals('', $cookie->getDomain());
        $this->assertFalse($cookie->isSecure());
        $this->assertFalse($cookie->isHttpOnly());

        $cookie = new ExpiredCookie('test', '/test', '*.test.com', true, true);

        $this->assertEquals('test', $cookie->getName());
        $this->assertEquals('', $cookie->getValue());
        $this->assertEquals(-31536000, $cookie->getExpiresTime());
        $this->assertEquals('/test', $cookie->getPath());
        $this->assertEquals('*.test.com', $cookie->getDomain());
        $this->assertTrue($cookie->isSecure());
        $this->assertTrue($cookie->isHttpOnly());
    }
}
