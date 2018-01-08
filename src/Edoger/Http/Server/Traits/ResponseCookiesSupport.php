<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Traits;

use Edoger\Util\Arr;
use Edoger\Http\Server\Response\Cookie;
use Edoger\Http\Server\Response\ExpiredCookie;

trait ResponseCookiesSupport
{
    /**
     * The response cookies collection.
     *
     * @var array
     */
    protected $cookies = [];

    /**
     * Determine if the cookies collection is empty.
     *
     * @return bool
     */
    public function isEmptyCookies(): bool
    {
        return empty($this->cookies);
    }

    /**
     * Determines whether the given cookie name exists in the cookies collection.
     *
     * @param string $name The response cookie name.
     *
     * @return bool
     */
    public function hasCookie(string $name): bool
    {
        return Arr::has($this->cookies, $name);
    }

    /**
     * Get a cookie instance from the current cookie collection.
     *
     * @param string $name The cookie name.
     *
     * @return Edoger\Http\Server\Response\Cookie|null
     */
    public function getCookie(string $name)
    {
        return Arr::get($this->cookies, $name);
    }

    /**
     * Get a cookie value from the current cookie collection.
     *
     * @param string $name The cookie name.
     *
     * @return string|null
     */
    public function getCookieValue(string $name)
    {
        $cookie = $this->getCookie($name);

        return $cookie ? $cookie->getValue() : $cookie;
    }

    /**
     * Get all the cookie instances.
     *
     * @return array
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }

    /**
     * Add a cookie instance.
     *
     * @param Edoger\Http\Server\Response\Cookie $cookie The cookie instance.
     *
     * @return void
     */
    public function addCookie(Cookie $cookie): void
    {
        $this->cookies[$cookie->getName()] = $cookie;
    }

    /**
     * Set a response cookie.
     *
     * @param string $name     The name of the cookie.
     * @param string $value    The value of the cookie.
     * @param int    $expire   The time the cookie expires.
     * @param string $path     The path on the server in which the cookie will be available on.
     * @param string $domain   The (sub)domain that the cookie is available to.
     * @param bool   $secure   Whether the cookie can only be transmitted on the client's secure HTTPS connection.
     * @param bool   $httpOnly Whether the cookie will only be accessible via the HTTP protocol.
     *
     * @return void
     */
    public function setCookie(string $name, string $value = '', int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httpOnly = false): void
    {
        $this->addCookie(new Cookie($name, $value, $expire, $path, $domain, $secure, $httpOnly));
    }

    /**
     * Set a long-term effective response cookie.
     *
     * @param string $name     The name of the cookie.
     * @param string $value    The value of the cookie.
     * @param string $path     The path on the server in which the cookie will be available on.
     * @param string $domain   The (sub)domain that the cookie is available to.
     * @param bool   $secure   Whether the cookie can only be transmitted on the client's secure HTTPS connection.
     * @param bool   $httpOnly Whether the cookie will only be accessible via the HTTP protocol.
     *
     * @return void
     */
    public function setForeverCookie(string $name, string $value = '', string $path = '/', string $domain = '', bool $secure = false, bool $httpOnly = false): void
    {
        // The cookie has 5 years of validity.
        $this->setCookie($name, $value, 157680000, $path, $domain, $secure, $httpOnly);
    }

    /**
     * Set a response session cookie.
     *
     * @param string $name     The name of the cookie.
     * @param string $value    The value of the cookie.
     * @param string $path     The path on the server in which the cookie will be available on.
     * @param string $domain   The (sub)domain that the cookie is available to.
     * @param bool   $secure   Whether the cookie can only be transmitted on the client's secure HTTPS connection.
     * @param bool   $httpOnly Whether the cookie will only be accessible via the HTTP protocol.
     *
     * @return void
     */
    public function setSessionCookie(string $name, string $value = '', string $path = '/', string $domain = '', bool $secure = false, bool $httpOnly = false): void
    {
        $this->setCookie($name, $value, 0, $path, $domain, $secure, $httpOnly);
    }

    /**
     * Remove a cookie instance from the current cookie collection.
     *
     * @param string $name The name of the cookie.
     *
     * @return self
     */
    public function removeCookie(string $name)
    {
        if ($this->hasCookie($name)) {
            unset($this->cookies[$name]);
        }

        return $this;
    }

    /**
     * Remove a cookie from client.
     *
     * @param string $name     The name of the cookie.
     * @param string $path     The path on the server in which the cookie will be available on.
     * @param string $domain   The (sub)domain that the cookie is available to.
     * @param bool   $secure   Whether the cookie can only be transmitted on the client's secure HTTPS connection.
     * @param bool   $httpOnly Whether the cookie will only be accessible via the HTTP protocol.
     *
     * @return self
     */
    public function removeCookieFromClient(string $name, string $path = '/', string $domain = '', bool $secure = false, bool $httpOnly = false)
    {
        $this->addCookie(new ExpiredCookie($name, $path, $domain, $secure, $httpOnly));

        return $this;
    }

    /**
     * Clear all cookies.
     *
     * @return self
     */
    public function clearCookies()
    {
        $this->cookies = [];

        return $this;
    }
}
