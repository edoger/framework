<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Traits;

use Edoger\Http\Server\Response\Headers;

trait ResponseHeadersSupport
{
    /**
     * The response headers collection.
     *
     * @var Edoger\Http\Server\Response\Headers
     */
    protected $headers;

    /**
     * Initialize the response headers collection.
     *
     * @param iterable $headers The response headers.
     *
     * @return void
     */
    protected function initResponseHeadersSupport(iterable $headers): void
    {
        $this->headers = new Headers($headers);
    }

    /**
     * Get the response headers collection.
     *
     * @return Edoger\Http\Server\Response\Headers
     */
    public function getHeaders(): Headers
    {
        return $this->headers;
    }

    /**
     * Determines whether the given header name exists in the response headers collection.
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
     * Gets the response header for the given header name.
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

    /**
     * Set a response header.
     *
     * @param string $name   The response header name.
     * @param string $header The response header.
     *
     * @return void
     */
    public function setHeader(string $name, string $header): void
    {
        $this->getHeaders()->set($name, $header);
    }

    /**
     * Remove a response header.
     *
     * @param string $name The given header name.
     *
     * @return self
     */
    public function removeHeader(string $name)
    {
        $this->getHeaders()->delete($name);

        return $this;
    }

    /**
     * Clear all response headers.
     *
     * @return self
     */
    public function clearHeaders()
    {
        $this->getHeaders()->clear();

        return $this;
    }

    /**
     * Replace all response headers.
     *
     * @param iterable $headers The response headers.
     *
     * @return self
     */
    public function replaceHeaders(iterable $headers)
    {
        $collection = $this->getHeaders()->clear();

        foreach ($headers as $name => $header) {
            $collection->set($name, $header);
        }

        return $this;
    }
}
