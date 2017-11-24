<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Response;

use Edoger\Util\Arr;
use Edoger\Http\Foundation\Headers as FoundationHeaders;

class Headers extends FoundationHeaders
{
    /**
     * Set a response header.
     *
     * @param string $name  The response header name.
     * @param string $value The response header value.
     *
     * @return void
     */
    public function set(string $name, string $value): void
    {
        $this->headers[$this->standardize($name)] = $value;
    }

    /**
     * Delete a response header for a given name.
     *
     * @param string $name The given response header name.
     *
     * @return self
     */
    public function delete(string $name): self
    {
        $name = $this->standardize($name);

        if (Arr::has($this->headers, $name)) {
            unset($this->headers[$name]);
        }

        return $this;
    }

    /**
     * Clear all response headers.
     *
     * @return self
     */
    public function clear(): self
    {
        $this->headers = [];

        return $this;
    }
}
