<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Foundation;

use Countable;
use ArrayIterator;
use Edoger\Util\Arr;
use Edoger\Util\Str;
use IteratorAggregate;
use Edoger\Util\Contracts\Arrayable;

class Headers implements Arrayable, Countable, IteratorAggregate
{
    /**
     * All the HTTP headers.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * The headers constructor.
     *
     * @param mixed $headers All the HTTP headers.
     *
     * @return void
     */
    public function __construct($headers = [])
    {
        foreach (Arr::convert($headers) as $name => $header) {
            $this->headers[$this->standardize($name)] = $header;
        }
    }

    /**
     * Standardize the given HTTP header name.
     *
     * @param string $name The given HTTP header name.
     *
     * @return string
     */
    protected function standardize(string $name): string
    {
        return str_replace('_', '-', Str::lower($name));
    }

    /**
     * Determines whether the given HTTP header name exists in the current headers collection.
     *
     * @param string $name The given HTTP header name.
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return Arr::has($this->headers, $this->standardize($name));
    }

    /**
     * Gets the HTTP header for the given HTTP header name.
     *
     * @param string $name    The given HTTP header name.
     * @param string $default The default value.
     *
     * @return string
     */
    public function get(string $name, string $default = ''): string
    {
        return Arr::get($this->headers, $this->standardize($name), $default);
    }

    /**
     * Returns the HTTP headers as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->headers;
    }

    /**
     * Gets the size of the HTTP headers.
     *
     * @return int
     */
    public function count()
    {
        return count($this->headers);
    }

    /**
     * Gets an iterator instance of the HTTP headers.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->headers);
    }
}
