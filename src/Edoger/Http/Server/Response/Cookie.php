<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Response;

class Cookie
{
    /**
     * The name of the cookie.
     *
     * @var string
     */
    protected $name;

    /**
     * The value of the cookie.
     *
     * @var string
     */
    protected $value;

    /**
     * The time the cookie expires.
     * If set to 0, the cookie will expire at the end of the session.
     *
     * @var integer
     */
    protected $expire;

    /**
     * The path on the server in which the cookie will be available on.
     *
     * @var string
     */
    protected $path;

    /**
     * The (sub)domain that the cookie is available to.
     *
     * @var string
     */
    protected $domain;

    /**
     * Whether the cookie can only be transmitted on the client's secure HTTPS connection.
     *
     * @var boolean
     */
    protected $secure;

    /**
     * Whether the cookie will only be accessible via the HTTP protocol.
     *
     * @var boolean
     */
    protected $httpOnly;

    /**
     * The cookie constructor.
     *
     * @param  string  $name     The name of the cookie.
     * @param  string  $value    The value of the cookie.
     * @param  integer $expire   The time the cookie expires.
     * @param  string  $path     The path on the server in which the cookie will be available on.
     * @param  string  $domain   The (sub)domain that the cookie is available to.
     * @param  boolean $secure   Whether the cookie can only be transmitted on the client's secure HTTPS connection.
     * @param  boolean $httpOnly Whether the cookie will only be accessible via the HTTP protocol.
     * @return void
     */
    public function __construct(string $name, string $value = '', int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httpOnly = false)
    {
        $this->name     = $name;
        $this->value    = $value;
        $this->expire   = $expire;
        $this->path     = $path;
        $this->domain   = $domain;
        $this->secure   = $secure;
        $this->httpOnly = $httpOnly;
    }

    /**
     * Gets the name of the cookie.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets the value of the cookie.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Gets the time the cookie expires.
     *
     * @return integer
     */
    public function getExpiresTime(): int
    {
        return $this->expire;
    }

    /**
     * Gets the path on the server in which the cookie will be available on.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Gets the (sub)domain that the cookie is available to.
     *
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * Determines whether the cookie can only be transmitted on the client's secure HTTPS connection.
     *
     * @return boolean
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * Determine whether the cookie will only be accessible through the HTTP protocol.
     *
     * @return boolean
     */
    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }
}
