<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Traits;

use Edoger\Http\Foundation\Collection;

trait RequestAttributesSupport
{
    /**
     * The client's request attributes collection.
     *
     * @var Edoger\Http\Foundation\Collection
     */
    protected $attributes;

    /**
     * Initialize the client's request attributes collection.
     *
     * @return void
     */
    protected function initRequestAttributesSupport(): void
    {
        $this->attributes = new Collection();
    }

    /**
     * Get the client's request attributes collection.
     *
     * @return Edoger\Http\Foundation\Collection
     */
    protected function getAttributes(): Collection
    {
        return $this->attributes;
    }

    /**
     * Get the request attribute value.
     *
     * @param string $name The request attribute name.
     *
     * @return mixed
     */
    protected function getAttribute(string $name)
    {
        return $this->getAttributes()->get($name);
    }

    /**
     * Put the request attribute.
     *
     * @param string $name  The request attribute name.
     * @param mixed  $value The request attribute value.
     *
     * @return mixed
     */
    protected function putAttribute(string $name, $value)
    {
        $this->getAttributes()->set($name, $value);

        return $value;
    }

    /**
     * Flush the request attributes.
     *
     * @return self
     */
    public function flushAttributes()
    {
        $this->getAttributes()->clear();

        // Update request header information.
        // Some of the attributes will depend on the request header,
        // which will not be updated without forcing the request header set to be rebuilt.
        $this->initRequestHeadersSupport();

        return $this;
    }
}
