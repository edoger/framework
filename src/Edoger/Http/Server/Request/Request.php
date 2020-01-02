<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Request;

use Edoger\Util\Arr;
use Edoger\Util\Str;
use Edoger\Http\Server\Globals\Body;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Http\Server\Globals\Query;
use Edoger\Http\Server\Globals\Server;
use Edoger\Http\Server\Globals\Cookies;
use Edoger\Http\Server\Traits\RequestExtrasSupport;
use Edoger\Http\Server\Traits\RequestHeadersSupport;
use Edoger\Http\Server\Traits\RequestAttributesSupport;
use Edoger\Http\Server\Traits\RequestPostContentSupport;

class Request implements Arrayable
{
    use RequestHeadersSupport,
        RequestExtrasSupport,
        RequestAttributesSupport,
        RequestPostContentSupport;

    /**
     * The server and execution environment variables collection.
     *
     * @var Server
     */
    protected $server;

    /**
     * The request body parameters collection.
     *
     * @var Body
     */
    protected $body;

    /**
     * The request query parameters collection.
     *
     * @var Query
     */
    protected $query;

    /**
     * The request cookies collection.
     *
     * @var Cookies
     */
    protected $cookies;

    /**
     * The request constructor.
     *
     * @param iterable $server  The server and execution environment variables.
     * @param iterable $body    The request body parameters.
     * @param iterable $query   The request query parameters.
     * @param iterable $cookies The request cookies.
     *
     * @return void
     */
    public function __construct(iterable $server, iterable $body, iterable $query, iterable $cookies)
    {
        $this->server  = Server::create($server);
        $this->body    = Body::create($body);
        $this->query   = Query::create($query);
        $this->cookies = Cookies::create($cookies);

        // Initialize the client's request headers collection.
        $this->initRequestHeadersSupport();

        // Initialize the client's request attributes collection.
        $this->initRequestAttributesSupport();

        // Initialize the client's original request post content.
        $this->initRequestPostContentSupport();
    }

    /**
     * Get the server and execution environment variables collection.
     *
     * @return Edoger\Http\Server\Globals\Server
     */
    public function getServer(): Server
    {
        return $this->server;
    }

    /**
     * Get the request body parameters collection.
     *
     * @return Edoger\Http\Server\Globals\Body
     */
    public function getBody(): Body
    {
        return $this->body;
    }

    /**
     * Get the request query parameters collection.
     *
     * @return Edoger\Http\Server\Globals\Query
     */
    public function getQuery(): Query
    {
        return $this->query;
    }

    /**
     * Get the request cookies collection.
     *
     * @return Edoger\Http\Server\Globals\Cookies
     */
    public function getCookies(): Cookies
    {
        return $this->cookies;
    }

    /**
     * Get the client's request protocol version.
     *
     * @return string
     */
    public function getRequestProtocolVersion(): string
    {
        if (null !== $version = $this->getAttribute('REQUEST_PROTOCOL_VERSION')) {
            return $version;
        }

        // If this property is not applicable, an empty string is returned.
        return $this->putAttribute(
            'REQUEST_PROTOCOL_VERSION',
            $this->getServer()->get('SERVER_PROTOCOL', '')
        );
    }

    /**
     * Determine if the current protocol version is equal to the given protocol version.
     *
     * @param string $version The given protocol version.
     *
     * @return bool
     */
    public function isRequestProtocolVersion(string $version): bool
    {
        if ('' === $protocolVersion = $this->getRequestProtocolVersion()) {
            return false;
        }

        // Automatically add "HTTP/" prefix.
        if (in_array($version, ['1.0', '1.1', '2.0'], true)) {
            $version = 'HTTP/'.$version;
        }

        return $version === $protocolVersion;
    }

    /**
     * Get the client's request path.
     *
     * @return string
     */
    public function getRequestPath(): string
    {
        if (null !== $path = $this->getAttribute('REQUEST_PATH')) {
            return $path;
        }

        $uri = $this->getServer()->getAny(['PATH_INFO', 'REQUEST_URI'], '/');

        // Removes the query string from the request uri.
        if (false !== $pos = Str::strpos($uri, '?')) {
            $uri = Str::substr($uri, 0, $pos);
        }

        return $this->putAttribute('REQUEST_PATH', rawurldecode($uri));
    }

    /**
     * Get the client's request path information.
     *
     * @return array
     */
    public function getRequestPathInfo(): array
    {
        if (null !== $info = $this->getAttribute('REQUEST_PATHINFO')) {
            return $info;
        }

        if ('/' === $path = $this->getRequestPath()) {
            return $this->putAttribute('REQUEST_PATHINFO', []);
        } else {
            return $this->putAttribute('REQUEST_PATHINFO', explode('/', trim($path, '/')));
        }
    }

    /**
     * Get the client's request method.
     *
     * @return string
     */
    public function getRequestMethod(): string
    {
        if (null !== $method = $this->getAttribute('REQUEST_METHOD')) {
            return $method;
        }

        return $this->putAttribute(
            'REQUEST_METHOD',
            Str::upper($this->getServer()->get('REQUEST_METHOD', 'GET'))
        );
    }

    /**
     * Determines whether the client's request method is a given request method.
     *
     * @param string $method The given request method.
     *
     * @return bool
     */
    public function isRequestMethod(string $method): bool
    {
        return Str::upper($method) === $this->getRequestMethod();
    }

    /**
     * Determines whether the current request is an https request.
     *
     * @return bool
     */
    public function isHttps(): bool
    {
        if (null !== $https = $this->getAttribute('REQUEST_IS_HTTPS')) {
            return $https;
        }

        $https = $this->getServer()->get('HTTPS');

        return $this->putAttribute(
            'REQUEST_IS_HTTPS',
            !empty($https) && 'off' !== Str::lower($https)
        );
    }

    /**
     * Get the current request scheme name.
     *
     * @return string
     */
    public function getRequestScheme(): string
    {
        if (null !== $scheme = $this->getAttribute('REQUEST_SCHEME')) {
            return $scheme;
        }

        if ($scheme = $this->getServer()->get('REQUEST_SCHEME')) {
            return $this->putAttribute('REQUEST_SCHEME', Str::lower($scheme));
        } else {
            return $this->putAttribute('REQUEST_SCHEME', $this->isHttps() ? 'https' : 'http');
        }
    }

    /**
     * Gets the client user agent string.
     *
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->getHeader('User-Agent');
    }

    /**
     * Gets the client referrer url.
     *
     * @return string
     */
    public function getReferer(): string
    {
        return $this->getHeader('Referer');
    }

    /**
     * Gets the client referrer url, alias of getReferer() method.
     *
     * @return string
     */
    public function getReferrer(): string
    {
        return $this->getReferer();
    }

    /**
     * Gets the client IP address.
     *
     * @return string
     */
    public function getClientIp(): string
    {
        if (null !== $ip = $this->getAttribute('REQUEST_CLIENT_IP')) {
            return $ip;
        }

        if ('' !== $ip = $this->getHeader('Client-Ip')) {
            return $this->putAttribute('REQUEST_CLIENT_IP', $ip);
        }

        if ('' !== $ips = $this->getHeader('X-Forwarded-For')) {
            return $this->putAttribute(
                'REQUEST_CLIENT_IP',
                trim(Arr::first(explode(',', $ips)))
            );
        }

        return $this->putAttribute(
            'REQUEST_CLIENT_IP',
            $this->getServer()->get('REMOTE_ADDR', '127.0.0.1')
        );
    }

    /**
     * Get the server IP address.
     *
     * @return string
     */
    public function getServerIp(): string
    {
        return $this->getServer()->get('SERVER_ADDR', '127.0.0.1');
    }

    /**
     * Get the server port number.
     *
     * @return int
     */
    public function getServerPort(): int
    {
        if (null !== $port = $this->getAttribute('REQUEST_SERVER_PORT')) {
            return $port;
        }

        if ($port = $this->getServer()->get('SERVER_PORT')) {
            return $this->putAttribute('REQUEST_SERVER_PORT', (int) $port);
        } else {
            return $this->putAttribute('REQUEST_SERVER_PORT', $this->isHttps() ? 443 : 80);
        }
    }

    /**
     * Determines whether the request is an AJAX request.
     *
     * @return bool
     */
    public function isAjax(): bool
    {
        if (null !== $ajax = $this->getAttribute('REQUEST_IS_AJAX')) {
            return $ajax;
        }

        return $this->putAttribute(
            'REQUEST_IS_AJAX',
            'XMLHTTPREQUEST' === Str::upper($this->getHeader('X-Requested-With'))
        );
    }

    /**
     * Get the host.
     *
     * @return string
     */
    public function getHost(): string
    {
        return Str::lower(trim($this->getHeader('Host', 'localhost')));
    }

    /**
     * Get the hostname (without port).
     *
     * @return string
     */
    public function getHostName(): string
    {
        return preg_replace('/:\d+$/', '', $this->getHost());
    }

    /**
     * Get the base URL from the current client request.
     *
     * @return string
     */
    public function getRequestBaseUrl(): string
    {
        if (null !== $url = $this->getAttribute('REQUEST_BASE_URL')) {
            return $url;
        }

        $port = $this->getServerPort();

        // For the default port, I do not think it should appear in the URL.
        if (80 === $port || 443 === $port) {
            $url = $this->getRequestScheme().'://'.$this->getHostName();
        } else {
            $url = $this->getRequestScheme().'://'.$this->getHostName().':'.$port;
        }

        return $this->putAttribute('REQUEST_BASE_URL', $url);
    }

    /**
     * Generate a request URL using the current request base URL.
     *
     * @param string $path  The URL path.
     * @param array  $query The URL query parameters.
     *
     * @return string
     */
    public function generateRequestUrl(string $path = '', array $query = []): string
    {
        // Remove extra slashes.
        if ('' !== $path) {
            $path = '/' === $path ? '' : '/'.trim($path, '/');
        }

        if (empty($query)) {
            return $this->getRequestBaseUrl().$path;
        } else {
            return $this->getRequestBaseUrl().$path.'?'.http_build_query($query, '', '&');
        }
    }

    /**
     * Get the URL from the current client request.
     *
     * @param bool $appendQueryString Whether to automatically append the query string.
     *
     * @return string
     */
    public function getRequestUrl(bool $appendQueryString = false): string
    {
        if ($appendQueryString) {
            return $this->generateRequestUrl($this->getRequestPath(), $this->getQuery()->toArray());
        } else {
            return $this->generateRequestUrl($this->getRequestPath());
        }
    }

    /**
     * Returns the extra data as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->getExtras();
    }
}
