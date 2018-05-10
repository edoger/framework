<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Http\Cases\Server\Response;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Http\Foundation\Collection;
use Edoger\Http\Server\Response\Cookie;
use Edoger\Http\Server\Response\Headers;
use Edoger\Http\Server\Response\Response;
use Edoger\Http\Server\Response\ExpiredCookie;
use EdogerTests\Http\Mocks\TestResponseRenderer;
use Edoger\Http\Server\Contracts\ResponseRenderer;
use Edoger\Http\Server\Traits\ResponseCookiesSupport;
use Edoger\Http\Server\Traits\ResponseHeadersSupport;
use Edoger\Http\Server\Traits\ResponseRendererSupport;
use Edoger\Http\Server\Response\Renderers\EmptyRenderer;
use EdogerTests\Http\Mocks\TestReturnTestKeyResponseRenderer;

class ResponseTest extends TestCase
{
    public function testResponseInstanceOfArrayable()
    {
        $response = new Response(200, []);

        $this->assertInstanceOf(Arrayable::class, $response);
    }

    public function testRequestUseTraitResponseHeadersSupport()
    {
        $uses = class_uses(new Response(200, []));

        $this->assertArrayHasKey(ResponseHeadersSupport::class, $uses);
        $this->assertEquals(ResponseHeadersSupport::class, $uses[ResponseHeadersSupport::class]);
    }

    public function testRequestUseTraitResponseCookiesSupport()
    {
        $uses = class_uses(new Response(200, []));

        $this->assertArrayHasKey(ResponseCookiesSupport::class, $uses);
        $this->assertEquals(ResponseCookiesSupport::class, $uses[ResponseCookiesSupport::class]);
    }

    public function testRequestUseTraitResponseRendererSupport()
    {
        $uses = class_uses(new Response(200, []));

        $this->assertArrayHasKey(ResponseRendererSupport::class, $uses);
        $this->assertEquals(ResponseRendererSupport::class, $uses[ResponseRendererSupport::class]);
    }

    public function testResponseGetStatusCode()
    {
        $response = new Response(200, []);
        $this->assertEquals(200, $response->getStatusCode());

        $response = new Response(500, []);
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testResponseSetStatusCode()
    {
        $response = new Response(200, []);
        $this->assertEquals(200, $response->getStatusCode());

        $response->setStatusCode(500);
        $this->assertEquals(500, $response->getStatusCode());

        $response->setStatusCode(400);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testResponseSetStatusCodeFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid response HTTP status code.');

        $response = new Response(200, []);

        $response->setStatusCode(1000); // exception
    }

    public function testResponseGetResponseContent()
    {
        $response = new Response(200, []);
        $content  = $response->getResponseContent();

        $this->assertInstanceOf(Collection::class, $content);
        $this->assertEquals([], $content->toArray());

        $response = new Response(200, ['test' => 'test']);
        $content  = $response->getResponseContent();

        $this->assertInstanceOf(Collection::class, $content);
        $this->assertEquals(['test' => 'test'], $content->toArray());
    }

    public function testResponseWithResponseContent()
    {
        $response = new Response(200, []);
        $content  = $response->getResponseContent();

        $this->assertEquals([], $content->toArray());
        $this->assertEquals($response, $response->withResponseContent(['test' => 'test']));
        $this->assertEquals(['test' => 'test'], $content->toArray());
    }

    public function testResponseClearResponseContent()
    {
        $response = new Response(200, ['foo' => 'foo', 'bar' => 'bar']);
        $content  = $response->getResponseContent();

        $this->assertEquals(['foo' => 'foo', 'bar' => 'bar'], $content->toArray());
        $this->assertEquals($response, $response->clearResponseContent());
        $this->assertEquals([], $content->toArray());
    }

    public function testResponseGetHeaders()
    {
        $response = new Response(200, []);
        $headers  = $response->getHeaders();

        $this->assertInstanceOf(Headers::class, $headers);
        $this->assertEquals([], $headers->toArray());

        $response = new Response(200, [], ['Test-Header' => 'test']);
        $headers  = $response->getHeaders();

        $this->assertInstanceOf(Headers::class, $headers);
        $this->assertEquals(['test-header' => 'test'], $headers->toArray());
    }

    public function testResponseHasHeader()
    {
        $response = new Response(200, [], ['Test-Header' => 'test']);

        $this->assertTrue($response->hasHeader('Test-Header'));
        $this->assertFalse($response->hasHeader('Test-Header-Non'));
    }

    public function testResponseGetHeader()
    {
        $response = new Response(200, [], ['Test-Header' => 'test']);

        $this->assertEquals('test', $response->getHeader('Test-Header'));
        $this->assertEquals('', $response->getHeader('Test-Header-Non'));
        $this->assertEquals('non', $response->getHeader('Test-Header-Non', 'non'));
    }

    public function testResponseSetHeader()
    {
        $response = new Response(200, []);

        $this->assertFalse($response->hasHeader('Test-Header'));
        $response->setHeader('Test-Header', 'test');
        $this->assertTrue($response->hasHeader('Test-Header'));
        $this->assertEquals('test', $response->getHeader('Test-Header'));
        $response->setHeader('Test-Header', 'foo');
        $this->assertEquals('foo', $response->getHeader('Test-Header'));
    }

    public function testResponseRemoveHeader()
    {
        $response = new Response(200, [], ['Test-Header' => 'test']);

        $this->assertTrue($response->hasHeader('Test-Header'));
        $this->assertEquals($response, $response->removeHeader('Test-Header'));
        $this->assertFalse($response->hasHeader('Test-Header'));
    }

    public function testResponseClearHeaders()
    {
        $response = new Response(200, [], ['Test-Header-Foo' => 'foo', 'Test-Header-Bar' => 'bar']);

        $this->assertEquals('foo', $response->getHeader('Test-Header-Foo'));
        $this->assertEquals('bar', $response->getHeader('Test-Header-Bar'));

        $this->assertEquals($response, $response->clearHeaders());

        $this->assertFalse($response->hasHeader('Test-Header-Foo'));
        $this->assertFalse($response->hasHeader('Test-Header-Bar'));
    }

    public function testResponseReplaceHeaders()
    {
        $response = new Response(200, [], ['Test-Header-Foo' => 'foo']);

        $this->assertEquals('foo', $response->getHeader('Test-Header-Foo'));
        $this->assertFalse($response->hasHeader('Test-Header-Bar'));

        $this->assertEquals($response, $response->replaceHeaders(['Test-Header-Bar' => 'bar']));

        $this->assertEquals('bar', $response->getHeader('Test-Header-Bar'));
        $this->assertFalse($response->hasHeader('Test-Header-Foo'));
    }

    public function testResponseIsEmptyCookies()
    {
        $response = new Response(200, []);

        $this->assertTrue($response->isEmptyCookies());
        $response->addCookie(new Cookie('test'));
        $this->assertFalse($response->isEmptyCookies());
    }

    public function testResponseHasCookie()
    {
        $response = new Response(200, []);

        $this->assertFalse($response->hasCookie('test'));
        $response->addCookie(new Cookie('test'));
        $this->assertTrue($response->hasCookie('test'));
    }

    public function testResponseGetCookie()
    {
        $response = new Response(200, []);
        $cookie   = new Cookie('test');

        $this->assertNull($response->getCookie('test'));
        $response->addCookie($cookie);
        $this->assertEquals($cookie, $response->getCookie('test'));
    }

    public function testResponseGetCookieValue()
    {
        $response = new Response(200, []);
        $cookie   = new Cookie('test', 'test');

        $this->assertNull($response->getCookieValue('test'));
        $response->addCookie($cookie);
        $this->assertEquals('test', $response->getCookieValue('test'));
    }

    public function testResponseGetCookies()
    {
        $response = new Response(200, []);
        $cookie   = new Cookie('test', 'test');

        $this->assertEquals([], $response->getCookies());
        $response->addCookie($cookie);
        $this->assertEquals(['test' => $cookie], $response->getCookies());
    }

    public function testResponseAddCookie()
    {
        $response = new Response(200, []);
        $cookieA  = new Cookie('testA', 'testA');
        $cookieB  = new Cookie('testB', 'testB');

        $this->assertEquals([], $response->getCookies());
        $response->addCookie($cookieA);
        $this->assertEquals(['testA' => $cookieA], $response->getCookies());
        $response->addCookie($cookieB);
        $this->assertEquals(['testA' => $cookieA, 'testB' => $cookieB], $response->getCookies());
    }

    public function testResponseSetCookie()
    {
        $response = new Response(200, []);

        $response->setCookie('test', 'test');
        $cookie = $response->getCookie('test');

        $this->assertInstanceOf(Cookie::class, $cookie);
        $this->assertEquals('test', $cookie->getName());
        $this->assertEquals('test', $cookie->getValue());
    }

    public function testResponseSetForeverCookie()
    {
        $response = new Response(200, []);

        $response->setForeverCookie('test', 'test');
        $cookie = $response->getCookie('test');

        $this->assertInstanceOf(Cookie::class, $cookie);
        $this->assertEquals('test', $cookie->getName());
        $this->assertEquals('test', $cookie->getValue());
        $this->assertEquals(157680000, $cookie->getExpiresTime());
    }

    public function testResponseSetSessionCookie()
    {
        $response = new Response(200, []);

        $response->setSessionCookie('test', 'test');
        $cookie = $response->getCookie('test');

        $this->assertInstanceOf(Cookie::class, $cookie);
        $this->assertEquals('test', $cookie->getName());
        $this->assertEquals('test', $cookie->getValue());
        $this->assertEquals(0, $cookie->getExpiresTime());
    }

    public function testResponseRemoveCookie()
    {
        $response = new Response(200, []);
        $cookie   = new Cookie('test', 'test');

        $response->addCookie($cookie);

        $this->assertTrue($response->hasCookie('test'));
        $this->assertEquals($response, $response->removeCookie('test'));
        $this->assertFalse($response->hasCookie('test'));
    }

    public function testResponseRemoveCookieFromClient()
    {
        $response = new Response(200, []);
        $cookie   = new Cookie('test', 'test');

        $response->addCookie($cookie);

        $this->assertTrue($response->hasCookie('test'));
        $this->assertEquals($cookie, $response->getCookie('test'));
        $this->assertEquals($response, $response->removeCookieFromClient('test'));
        $this->assertTrue($response->hasCookie('test'));

        $newCookie = $response->getCookie('test');
        $this->assertNotEquals($cookie, $newCookie);
        $this->assertInstanceOf(ExpiredCookie::class, $newCookie);
        $this->assertEquals('test', $newCookie->getName());
        $this->assertEquals('', $newCookie->getValue());
        $this->assertEquals(-31536000, $newCookie->getExpiresTime());
    }

    public function testResponseClearCookiest()
    {
        $response = new Response(200, []);
        $cookie   = new Cookie('test', 'test');

        $response->addCookie($cookie);
        $response->setCookie('foo', 'foo');

        $this->assertTrue($response->hasCookie('test'));
        $this->assertTrue($response->hasCookie('foo'));

        $this->assertEquals($response, $response->clearCookies());

        $this->assertFalse($response->hasCookie('test'));
        $this->assertFalse($response->hasCookie('foo'));
        $this->assertTrue($response->isEmptyCookies());
    }

    public function testResponseSetResponseRenderer()
    {
        $response = new Response(200, []);
        $renderer = new TestResponseRenderer();

        $response->setResponseRenderer($renderer);
        $this->assertEquals($renderer, $response->getResponseRenderer());
    }

    public function testResponseGetResponseRenderer()
    {
        $response = new Response(200, []);

        $this->assertInstanceOf(ResponseRenderer::class, $response->getResponseRenderer());
        $this->assertInstanceOf(EmptyRenderer::class, $response->getResponseRenderer());

        $renderer = new TestResponseRenderer();
        $response->setResponseRenderer($renderer);

        $this->assertEquals($renderer, $response->getResponseRenderer());
    }

    public function testResponseRemoveResponseRenderer()
    {
        $response = new Response(200, []);
        $renderer = new TestResponseRenderer();

        $response->setResponseRenderer($renderer);

        $this->assertInstanceOf(TestResponseRenderer::class, $response->getResponseRenderer());
        $this->assertEquals($renderer, $response->getResponseRenderer());

        $response->removeResponseRenderer($renderer);

        $this->assertInstanceOf(EmptyRenderer::class, $response->getResponseRenderer());
    }

    public function testResponseSendHeaders()
    {
        $response = new Response(200, []);
        $this->assertEquals($response, $response->sendHeaders());
    }

    public function testResponseSendBody()
    {
        $this->expectOutputString('');

        $response = new Response(200, []);
        $this->assertEquals($response, $response->sendBody());
    }

    public function testResponseSendBodyWithRenderer()
    {
        $this->expectOutputString(print_r(['foo' => 'foo'], true));

        $renderer = new TestResponseRenderer();
        $response = new Response(200, ['foo' => 'foo']);

        $response->setResponseRenderer($renderer);

        $this->assertEquals($response, $response->sendBody());
    }

    public function testResponseSendBodyWithHandler()
    {
        $renderer = new TestReturnTestKeyResponseRenderer();
        $response = new Response(200, ['test' => 'test']);

        $response->setResponseRenderer($renderer);
        $called = false;

        $this->assertEquals($response, $response->sendBody(function ($body) use (&$called) {
            $called = true;
            $this->assertEquals('test', $body);
        }));
        $this->assertTrue($called);

        // Clear all response content.
        $response->clearResponseContent();
        $called = false;

        $this->assertEquals($response, $response->sendBody(function ($body) use (&$called) {
            $called = true;
            $this->assertEquals('', $body);
        }));
        $this->assertTrue($called);
    }

    public function testResponseArrayable()
    {
        $response = new Response(200, []);
        $this->assertEquals([], $response->toArray());

        $response = new Response(200, ['foo' => 'foo']);
        $this->assertEquals(['foo' => 'foo'], $response->toArray());
    }
}
