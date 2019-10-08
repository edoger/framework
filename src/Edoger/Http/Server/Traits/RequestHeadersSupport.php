<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Traits;

use Edoger\Http\Server\Request\Headers;

trait RequestHeadersSupport
{
    /**
     * The request headers collection.
     *
     * @var Headers
     */
    protected $headers;

    /**
     * Initialize the client's request headers collection.
     *
     * @return void
     */
    protected function initRequestHeadersSupport(): void
    {
        $this->headers = Headers::create($this->getServer());
    }

    /**
     * Get the request headers collection.
     *
     * @return Headers
     */
    public function getHeaders(): Headers
    {
        return $this->headers;
    }

    /**
     * Determines whether the given header name exists in the request headers collection.
     *
     * @param string $name The given header name.
     *
     * @return bool
     */
    public function hasHeader(string $name): bool
    {
        return $this->getHeaders()->has($name);
    }

    /**
     * Gets the request header for the given header name.
     *
     * @param string $name    The given header name.
     * @param string $default The default value.
     *
     * @return string
     */
    public function getHeader(string $name, string $default = ''): string
    {
        return $this->getHeaders()->get($name, $default);
    }
}
