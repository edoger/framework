<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Http\Cases\Server\Request;

use PHPUnit\Framework\TestCase;
use Edoger\Http\Server\Globals\Body;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Http\Server\Globals\Query;
use Edoger\Http\Server\Globals\Server;
use Edoger\Http\Server\Globals\Cookies;
use Edoger\Http\Server\Request\Headers;
use Edoger\Http\Server\Request\Request;
use Edoger\Http\Server\Traits\RequestExtrasSupport;
use Edoger\Http\Server\Traits\RequestHeadersSupport;
use Edoger\Http\Server\Traits\RequestAttributesSupport;

class RequestTest extends TestCase
{
    protected $server;
    protected $body;
    protected $query;
    protected $cookies;

    protected function setUp()
    {
        $this->server = [
            'HTTP_ACCEPT_LANGUAGE'           => 'zh-CN,zh;q=0.8,ko;q=0.6,en;q=0.4',
            'HTTP_ACCEPT_ENCODING'           => 'gzip, deflate',
            'HTTP_ACCEPT'                    => '*/*',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_USER_AGENT'                => 'Edoger/1.x',
            'HTTP_CACHE_CONTROL'             => 'max-age=0',
            'HTTP_CONNECTION'                => 'keep-alive',
            'HTTP_HOST'                      => 'www.test.org',
            'REDIRECT_STATUS'                => '200',
            'SERVER_NAME'                    => 'www.test.org',
            'SERVER_PORT'                    => '80',
            'SERVER_ADDR'                    => '127.0.0.1',
            'REMOTE_PORT'                    => '60702',
            'REMOTE_ADDR'                    => '127.0.0.1',
            'SERVER_SOFTWARE'                => 'nginx/1.13.5',
            'GATEWAY_INTERFACE'              => 'CGI/1.1',
            'REQUEST_SCHEME'                 => 'http',
            'SERVER_PROTOCOL'                => 'HTTP/1.1',
            'DOCUMENT_ROOT'                  => '/home/test/www',
            'DOCUMENT_URI'                   => '/index.php',
            'REQUEST_URI'                    => '/',
            'SCRIPT_NAME'                    => '/index.php',
            'CONTENT_LENGTH'                 => '',
            'CONTENT_TYPE'                   => '',
            'REQUEST_METHOD'                 => 'GET',
            'QUERY_STRING'                   => '',
            'SCRIPT_FILENAME'                => '/home/test/www/public/index.php',
            'FCGI_ROLE'                      => 'RESPONDER',
            'PHP_SELF'                       => '/index.php',
            'REQUEST_TIME_FLOAT'             => 1507046548.497781,
            'REQUEST_TIME'                   => 1507046548,
        ];
        $this->body    = ['test' => 'body'];
        $this->query   = ['test' => 'query'];
        $this->cookies = ['test' => 'cookie'];
    }

    protected function tearDown()
    {
        $this->server  = null;
        $this->body    = null;
        $this->query   = null;
        $this->cookies = null;
    }

    protected function createRequest()
    {
        return new Request($this->server, $this->body, $this->query, $this->cookies);
    }

    public function testRequestInstanceOfArrayable()
    {
        $this->assertInstanceOf(Arrayable::class, $this->createRequest());
    }

    public function testRequestUseTraitRequestAttributesSupport()
    {
        $uses = class_uses($this->createRequest());

        $this->assertArrayHasKey(RequestAttributesSupport::class, $uses);
        $this->assertEquals(RequestAttributesSupport::class, $uses[RequestAttributesSupport::class]);
    }

    public function testRequestUseTraitRequestExtrasSupport()
    {
        $uses = class_uses($this->createRequest());

        $this->assertArrayHasKey(RequestExtrasSupport::class, $uses);
        $this->assertEquals(RequestExtrasSupport::class, $uses[RequestExtrasSupport::class]);
    }

    public function testRequestUseTraitRequestHeadersSupport()
    {
        $uses = class_uses($this->createRequest());

        $this->assertArrayHasKey(RequestHeadersSupport::class, $uses);
        $this->assertEquals(RequestHeadersSupport::class, $uses[RequestHeadersSupport::class]);
    }

    public function testRequestGetServer()
    {
        $this->assertInstanceOf(Server::class, $this->createRequest()->getServer());
        $this->assertEquals($this->server, $this->createRequest()->getServer()->toArray());
    }

    public function testRequestGetBody()
    {
        $this->assertInstanceOf(Body::class, $this->createRequest()->getBody());
        $this->assertEquals($this->body, $this->createRequest()->getBody()->toArray());
    }

    public function testRequestGetQuery()
    {
        $this->assertInstanceOf(Query::class, $this->createRequest()->getQuery());
        $this->assertEquals($this->query, $this->createRequest()->getQuery()->toArray());
    }

    public function testRequestGetCookies()
    {
        $this->assertInstanceOf(Cookies::class, $this->createRequest()->getCookies());
        $this->assertEquals($this->cookies, $this->createRequest()->getCookies()->toArray());
    }

    public function testRequestGetHeaders()
    {
        $this->assertInstanceOf(Headers::class, $this->createRequest()->getHeaders());
        $this->assertEquals([
            'accept-language'           => 'zh-CN,zh;q=0.8,ko;q=0.6,en;q=0.4',
            'accept-encoding'           => 'gzip, deflate',
            'accept'                    => '*/*',
            'upgrade-insecure-requests' => '1',
            'user-agent'                => 'Edoger/1.x',
            'cache-control'             => 'max-age=0',
            'connection'                => 'keep-alive',
            'host'                      => 'www.test.org',
            'content-length'            => '',
            'content-type'              => '',
        ], $this->createRequest()->getHeaders()->toArray());
    }

    public function testRequestHasHeader()
    {
        foreach ([
            'accept-language',
            'accept-encoding',
            'accept',
            'upgrade-insecure-requests',
            'user-agent',
            'cache-control',
            'connection',
            'host',
            'content-length',
            'content-type',
        ] as $name) {
            $this->assertTrue($this->createRequest()->hasHeader($name));
        }

        $this->assertFalse($this->createRequest()->hasHeader('Not-Exists-Header-Name'));
    }

    public function testRequestGetHeader()
    {
        foreach ([
            'accept-language' => 'zh-CN,zh;q=0.8,ko;q=0.6,en;q=0.4',
            'accept-encoding' => 'gzip, deflate',
            'accept' => '*/*',
            'upgrade-insecure-requests' => '1',
            'user-agent' => 'Edoger/1.x',
            'cache-control' => 'max-age=0',
            'connection' => 'keep-alive',
            'host' => 'www.test.org',
            'content-length' => '',
            'content-type' => '',
        ] as $name => $header) {
            $this->assertEquals($header, $this->createRequest()->getHeader($name));
        }

        $this->assertEquals('', $this->createRequest()->getHeader('Not-Exists-Header-Name'));
        $this->assertEquals('Default', $this->createRequest()->getHeader('Not-Exists-Header-Name', 'Default'));
    }

    public function testRequestGetRequestProtocolVersion()
    {
        $this->assertEquals('HTTP/1.1', $this->createRequest()->getRequestProtocolVersion());

        $this->server['SERVER_PROTOCOL'] = 'HTTP/1.0';
        $this->assertEquals('HTTP/1.0', $this->createRequest()->getRequestProtocolVersion());

        $this->server['SERVER_PROTOCOL'] = 'HTTP/2.0';
        $this->assertEquals('HTTP/2.0', $this->createRequest()->getRequestProtocolVersion());

        unset($this->server['SERVER_PROTOCOL']);

        $this->assertEquals('', $this->createRequest()->getRequestProtocolVersion());
    }

    public function testRequestIsRequestProtocolVersion()
    {
        $this->assertTrue($this->createRequest()->isRequestProtocolVersion('HTTP/1.1'));
        $this->assertTrue($this->createRequest()->isRequestProtocolVersion('1.1'));
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('HTTP/1.0'));
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('1.0'));
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('HTTP/2.0'));
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('2.0'));

        $this->server['SERVER_PROTOCOL'] = 'HTTP/1.0';
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('HTTP/1.1'));
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('1.1'));
        $this->assertTrue($this->createRequest()->isRequestProtocolVersion('HTTP/1.0'));
        $this->assertTrue($this->createRequest()->isRequestProtocolVersion('1.0'));
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('HTTP/2.0'));
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('2.0'));

        $this->server['SERVER_PROTOCOL'] = 'HTTP/2.0';
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('HTTP/1.1'));
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('1.1'));
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('HTTP/1.0'));
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('1.0'));
        $this->assertTrue($this->createRequest()->isRequestProtocolVersion('HTTP/2.0'));
        $this->assertTrue($this->createRequest()->isRequestProtocolVersion('2.0'));

        unset($this->server['SERVER_PROTOCOL']);

        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('HTTP/1.1'));
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('1.1'));
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('HTTP/1.0'));
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('1.0'));
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('HTTP/2.0'));
        $this->assertFalse($this->createRequest()->isRequestProtocolVersion('2.0'));
    }

    public function testRequestGetRequestPath()
    {
        $this->assertEquals('/', $this->createRequest()->getRequestPath());

        $this->server['REQUEST_URI'] = '/test/foo';
        $this->assertEquals('/test/foo', $this->createRequest()->getRequestPath());

        $this->server['REQUEST_URI'] = '/test/foo?a=1&b=2';
        $this->assertEquals('/test/foo', $this->createRequest()->getRequestPath());

        $this->server['PATH_INFO'] = '/test/bar';
        $this->assertEquals('/test/bar', $this->createRequest()->getRequestPath());

        unset($this->server['PATH_INFO'], $this->server['REQUEST_URI']);

        $this->assertEquals('/', $this->createRequest()->getRequestPath());
    }

    public function testRequestGetRequestPathInfo()
    {
        $this->assertEquals([], $this->createRequest()->getRequestPathInfo());

        $this->server['REQUEST_URI'] = '/test/foo/bar/baz?a=1&b=2';
        $this->assertEquals(['test', 'foo', 'bar', 'baz'], $this->createRequest()->getRequestPathInfo());

        unset($this->server['REQUEST_URI']);
        $this->assertEquals([], $this->createRequest()->getRequestPathInfo());
    }

    public function testRequestGetRequestMethod()
    {
        $this->assertEquals('GET', $this->createRequest()->getRequestMethod());

        $this->server['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('POST', $this->createRequest()->getRequestMethod());

        unset($this->server['REQUEST_METHOD']);
        $this->assertEquals('GET', $this->createRequest()->getRequestMethod());
    }

    public function testRequestIsRequestMethod()
    {
        $this->assertTrue($this->createRequest()->isRequestMethod('GET'));
        $this->assertTrue($this->createRequest()->isRequestMethod('get'));
        $this->assertFalse($this->createRequest()->isRequestMethod('POST'));
        $this->assertFalse($this->createRequest()->isRequestMethod('post'));
    }

    public function testRequestIsHttps()
    {
        $this->assertFalse($this->createRequest()->isHttps());

        $this->server['HTTPS'] = 'off';
        $this->assertFalse($this->createRequest()->isHttps());

        $this->server['HTTPS'] = 'OFF';
        $this->assertFalse($this->createRequest()->isHttps());

        $this->server['HTTPS'] = 'on';
        $this->asserttrue($this->createRequest()->isHttps());

        $this->server['HTTPS'] = '1';
        $this->asserttrue($this->createRequest()->isHttps());
    }

    public function testRequestGetRequestScheme()
    {
        $this->assertEquals('http', $this->createRequest()->getRequestScheme());

        $this->server['REQUEST_SCHEME'] = 'https';
        $this->assertEquals('https', $this->createRequest()->getRequestScheme());

        unset($this->server['REQUEST_SCHEME']);
        $this->assertEquals('http', $this->createRequest()->getRequestScheme());

        $this->server['HTTPS'] = 'on';
        $this->assertEquals('https', $this->createRequest()->getRequestScheme());
    }

    public function testRequestGetUserAgent()
    {
        $this->assertEquals('Edoger/1.x', $this->createRequest()->getUserAgent());

        $this->server['HTTP_USER_AGENT'] = 'Edoger/1.0.x';
        $this->assertEquals('Edoger/1.0.x', $this->createRequest()->getUserAgent());

        unset($this->server['HTTP_USER_AGENT']);
        $this->assertEquals('', $this->createRequest()->getUserAgent());
    }

    public function testRequestGetReferer()
    {
        $this->assertEquals('', $this->createRequest()->getReferer());

        $this->server['HTTP_REFERER'] = 'http://www.foo.com/';
        $this->assertEquals('http://www.foo.com/', $this->createRequest()->getReferer());
    }

    public function testRequestGetReferrer()
    {
        $this->assertEquals('', $this->createRequest()->getReferrer());

        $this->server['HTTP_REFERER'] = 'http://www.foo.com/';
        $this->assertEquals('http://www.foo.com/', $this->createRequest()->getReferrer());
    }

    public function testRequestGetClientIp()
    {
        $this->assertEquals('127.0.0.1', $this->createRequest()->getClientIp());

        $this->server['HTTP_CLIENT_IP'] = '192.168.1.2';
        $this->assertEquals('192.168.1.2', $this->createRequest()->getClientIp());

        unset($this->server['HTTP_CLIENT_IP']);
        $this->server['HTTP_X_FORWARDED_FOR'] = '192.168.1.3';
        $this->assertEquals('192.168.1.3', $this->createRequest()->getClientIp());

        unset($this->server['HTTP_X_FORWARDED_FOR'], $this->server['REMOTE_ADDR']);

        $this->assertEquals('127.0.0.1', $this->createRequest()->getClientIp());

        $this->server['REMOTE_ADDR'] = '192.168.1.4';
        $this->assertEquals('192.168.1.4', $this->createRequest()->getClientIp());
    }

    public function testRequestGetServerIp()
    {
        $this->assertEquals('127.0.0.1', $this->createRequest()->getServerIp());

        $this->server['SERVER_ADDR'] = '192.168.1.2';
        $this->assertEquals('192.168.1.2', $this->createRequest()->getServerIp());

        unset($this->server['SERVER_ADDR']);
        $this->assertEquals('127.0.0.1', $this->createRequest()->getServerIp());
    }

    public function testRequestGetServerPort()
    {
        $this->assertEquals(80, $this->createRequest()->getServerPort());

        $this->server['SERVER_PORT'] = '81';
        $this->assertEquals(81, $this->createRequest()->getServerPort());

        unset($this->server['SERVER_PORT']);
        $this->assertEquals(80, $this->createRequest()->getServerPort());

        $this->server['HTTPS'] = 'on';
        $this->assertEquals(443, $this->createRequest()->getServerPort());
    }

    public function testRequestIsAjax()
    {
        $this->assertFalse($this->createRequest()->isAjax());

        $this->server['HTTP_X_REQUESTED_WITH'] = 'XmlHttpRequest';
        $this->assertTrue($this->createRequest()->isAjax());

        $this->server['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        $this->assertTrue($this->createRequest()->isAjax());

        $this->server['HTTP_X_REQUESTED_WITH'] = 'XMLHTTPREQUEST';
        $this->assertTrue($this->createRequest()->isAjax());

        $this->server['HTTP_X_REQUESTED_WITH'] = 'NON';
        $this->assertFalse($this->createRequest()->isAjax());
    }

    public function testRequestGetHost()
    {
        $this->assertEquals('www.test.org', $this->createRequest()->getHost());

        $this->server['HTTP_HOST'] = 'www.test.com';
        $this->assertEquals('www.test.com', $this->createRequest()->getHost());

        $this->server['HTTP_HOST'] = 'www.test.com:8080';
        $this->assertEquals('www.test.com:8080', $this->createRequest()->getHost());

        $this->server['HTTP_HOST'] = ' www.TEST.com ';
        $this->assertEquals('www.test.com', $this->createRequest()->getHost());

        unset($this->server['HTTP_HOST']);
        $this->assertEquals('localhost', $this->createRequest()->getHost());
    }

    public function testRequestGetHostName()
    {
        $this->assertEquals('www.test.org', $this->createRequest()->getHostName());

        $this->server['HTTP_HOST'] = 'www.test.com';
        $this->assertEquals('www.test.com', $this->createRequest()->getHostName());

        $this->server['HTTP_HOST'] = 'www.test.com:8080';
        $this->assertEquals('www.test.com', $this->createRequest()->getHostName());

        $this->server['HTTP_HOST'] = ' www.TEST.com ';
        $this->assertEquals('www.test.com', $this->createRequest()->getHostName());

        unset($this->server['HTTP_HOST']);
        $this->assertEquals('localhost', $this->createRequest()->getHostName());
    }

    public function testRequestGetRequestBaseUrl()
    {
        $this->assertEquals('http://www.test.org', $this->createRequest()->getRequestBaseUrl());

        $this->server['REQUEST_SCHEME'] = 'https';
        $this->assertEquals('https://www.test.org', $this->createRequest()->getRequestBaseUrl());

        $this->server['SERVER_PORT'] = 8080;
        $this->assertEquals('https://www.test.org:8080', $this->createRequest()->getRequestBaseUrl());
    }

    public function testRequestGenerateRequestUrl()
    {
        $this->assertEquals('http://www.test.org', $this->createRequest()->generateRequestUrl());
        $this->assertEquals('http://www.test.org', $this->createRequest()->generateRequestUrl('/'));
        $this->assertEquals('http://www.test.org/test', $this->createRequest()->generateRequestUrl('/test'));
        $this->assertEquals('http://www.test.org?a=b', $this->createRequest()->generateRequestUrl('/', ['a' => 'b']));
        $this->assertEquals('http://www.test.org/test?a=b', $this->createRequest()->generateRequestUrl('/test', ['a' => 'b']));

        $this->server['REQUEST_SCHEME'] = 'https';
        $this->assertEquals('https://www.test.org', $this->createRequest()->generateRequestUrl());
        $this->assertEquals('https://www.test.org', $this->createRequest()->generateRequestUrl('/'));
        $this->assertEquals('https://www.test.org/test', $this->createRequest()->generateRequestUrl('/test'));
        $this->assertEquals('https://www.test.org?a=b', $this->createRequest()->generateRequestUrl('/', ['a' => 'b']));
        $this->assertEquals('https://www.test.org/test?a=b', $this->createRequest()->generateRequestUrl('/test', ['a' => 'b']));

        $this->server['SERVER_PORT'] = 8080;
        $this->assertEquals('https://www.test.org:8080', $this->createRequest()->generateRequestUrl());
        $this->assertEquals('https://www.test.org:8080', $this->createRequest()->generateRequestUrl('/'));
        $this->assertEquals('https://www.test.org:8080/test', $this->createRequest()->generateRequestUrl('/test'));
        $this->assertEquals('https://www.test.org:8080?a=b', $this->createRequest()->generateRequestUrl('/', ['a' => 'b']));
        $this->assertEquals('https://www.test.org:8080/test?a=b', $this->createRequest()->generateRequestUrl('/test', ['a' => 'b']));
    }

    public function testRequestGetRequestUrl()
    {
        $this->assertEquals('http://www.test.org', $this->createRequest()->getRequestUrl());
        $this->assertEquals('http://www.test.org?test=query', $this->createRequest()->getRequestUrl(true));

        $this->server['REQUEST_URI'] = '/test';
        $this->assertEquals('http://www.test.org/test', $this->createRequest()->getRequestUrl());
        $this->assertEquals('http://www.test.org/test?test=query', $this->createRequest()->getRequestUrl(true));

        $this->server['REQUEST_SCHEME'] = 'https';
        $this->assertEquals('https://www.test.org/test', $this->createRequest()->getRequestUrl());
        $this->assertEquals('https://www.test.org/test?test=query', $this->createRequest()->getRequestUrl(true));

        $this->server['SERVER_PORT'] = 8080;
        $this->assertEquals('https://www.test.org:8080/test', $this->createRequest()->getRequestUrl());
        $this->assertEquals('https://www.test.org:8080/test?test=query', $this->createRequest()->getRequestUrl(true));
    }

    public function testRequestFlushAttributes()
    {
        $request = $this->createRequest();

        $this->assertEquals('/', $request->getRequestPath());
        $this->assertEquals([], $request->getRequestPathInfo());
        $this->assertEquals('GET', $request->getRequestMethod());
        $this->assertFalse($request->isHttps());
        $this->assertEquals('http', $request->getRequestScheme());
        $this->assertEquals('127.0.0.1', $request->getClientIp());
        $this->assertEquals(80, $request->getServerPort());
        $this->assertFalse($request->isAjax());

        $request->getServer()->set('REQUEST_URI', '/test/foo');
        $request->getServer()->set('REQUEST_METHOD', 'POST');
        $request->getServer()->set('HTTPS', 'on');
        $request->getServer()->set('REQUEST_SCHEME', 'https');
        $request->getServer()->set('HTTP_CLIENT_IP', '192.168.1.2');
        $request->getServer()->set('SERVER_PORT', '443');
        $request->getServer()->set('HTTP_X_REQUESTED_WITH', 'XmlHttpRequest');

        $this->assertEquals('/', $request->getRequestPath());
        $this->assertEquals([], $request->getRequestPathInfo());
        $this->assertEquals('GET', $request->getRequestMethod());
        $this->assertFalse($request->isHttps());
        $this->assertEquals('http', $request->getRequestScheme());
        $this->assertEquals('127.0.0.1', $request->getClientIp());
        $this->assertEquals(80, $request->getServerPort());
        $this->assertFalse($request->isAjax());

        $this->assertEquals($request, $request->flushAttributes());

        $this->assertEquals('/test/foo', $request->getRequestPath());
        $this->assertEquals(['test', 'foo'], $request->getRequestPathInfo());
        $this->assertEquals('POST', $request->getRequestMethod());
        $this->assertTrue($request->isHttps());
        $this->assertEquals('https', $request->getRequestScheme());
        $this->assertEquals('192.168.1.2', $request->getClientIp());
        $this->assertEquals(443, $request->getServerPort());
        $this->assertTrue($request->isAjax());
    }

    public function testRequestIsEmptyExtras()
    {
        $request = $this->createRequest();

        $this->assertTrue($request->isEmptyExtras());

        $request->setExtra('test', 'test');
        $this->assertFalse($request->isEmptyExtras());
    }

    public function testRequestHasExtra()
    {
        $request = $this->createRequest();

        $this->assertFalse($request->hasExtra('test'));

        $request->setExtra('test', 'test');
        $this->assertTrue($request->hasExtra('test'));
    }

    public function testRequestGetExtra()
    {
        $request = $this->createRequest();

        $this->assertNull($request->getExtra('test'));
        $this->assertEquals('default', $request->getExtra('test', 'default'));

        $request->setExtra('test', 'test');
        $this->assertEquals('test', $request->getExtra('test'));
    }

    public function testRequestGetExtras()
    {
        $request = $this->createRequest();

        $this->assertEquals([], $request->getExtras());

        $request->setExtra('test', 'test');
        $this->assertEquals(['test' => 'test'], $request->getExtras());
    }

    public function testRequestSetExtra()
    {
        $request = $this->createRequest();

        $request->setExtra('test', 'test');
        $this->assertEquals(['test' => 'test'], $request->getExtras());

        $request->setExtra('test', 'foo');
        $this->assertEquals(['test' => 'foo'], $request->getExtras());

        $request->setExtra('bar', 'bar');
        $this->assertEquals(['test' => 'foo', 'bar' => 'bar'], $request->getExtras());
    }

    public function testRequestReplaceExtras()
    {
        $request = $this->createRequest();

        $this->assertEquals([], $request->getExtras());
        $this->assertEquals($request, $request->replaceExtras(['test' => 'foo', 'bar' => 'bar']));
        $this->assertEquals(['test' => 'foo', 'bar' => 'bar'], $request->getExtras());
        $this->assertEquals($request, $request->replaceExtras(['test' => 'test']));
        $this->assertEquals(['test' => 'test'], $request->getExtras());
    }

    public function testRequestDeleteExtra()
    {
        $request = $this->createRequest();

        $request->setExtra('test', 'test');
        $this->assertEquals(['test' => 'test'], $request->getExtras());

        $request->deleteExtra('test');
        $this->assertEquals([], $request->getExtras());
    }

    public function testRequestClearExtras()
    {
        $request = $this->createRequest();

        $request->setExtra('test', 'test');
        $request->setExtra('foo', 'foo');
        $this->assertEquals(['test' => 'test', 'foo' => 'foo'], $request->getExtras());
        $this->assertEquals($request, $request->clearExtras());
        $this->assertEquals([], $request->getExtras());
    }

    public function testRequestCountExtras()
    {
        $request = $this->createRequest();

        $this->assertEquals(0, $request->countExtras());

        $request->setExtra('test', 'test');
        $this->assertEquals(1, $request->countExtras());

        $request->setExtra('foo', 'foo');
        $this->assertEquals(2, $request->countExtras());
    }

    public function testRequestGetPostContent()
    {
        $request = $this->createRequest();

        $this->assertEquals('', $request->getPostContent());
    }

    public function testRequestSetPostContent()
    {
        $request = $this->createRequest();

        $this->assertEquals('', $request->getPostContent());
        $request->setPostContent('{"foo":"foo"}');
        $this->assertEquals('{"foo":"foo"}', $request->getPostContent());
    }

    public function testRequestArrayable()
    {
        $request = $this->createRequest();

        $request->setExtra('test', 'test');
        $request->setExtra('foo', 'foo');

        $this->assertEquals(['test' => 'test', 'foo' => 'foo'], $request->toArray());
    }
}
