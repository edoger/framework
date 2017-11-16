<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Response;

class ExpiredCookie extends Cookie
{
    /**
     * The expired cookie constructor.
     *
     * @param  string  $name     The name of the cookie.
     * @param  string  $path     The path on the server in which the cookie will be available on.
     * @param  string  $domain   The (sub)domain that the cookie is available to.
     * @param  boolean $secure   Whether the cookie can only be transmitted on the client's secure HTTPS connection.
     * @param  boolean $httpOnly Whether the cookie will only be accessible via the HTTP protocol.
     * @return void
     */
    public function __construct(string $name, string $path = '/', string $domain = '', bool $secure = false, bool $httpOnly = false)
    {
        // Set the expiration date to one year ago.
        parent::__construct($name, '', -31536000, $path, $domain, $secure, $httpOnly);
    }
}
