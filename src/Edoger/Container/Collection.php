<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Container;

use ArrayIterator;
use IteratorAggregate;

class Collection extends Container implements IteratorAggregate
{
    /**
     * Sets the value of the given key in the current collection.
     * If the given key does not exist in the current collection, the system will automatically
     * create the given key.
     *
     * @param string $key   The given key.
     * @param mixed  $value The given value.
     *
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->elements[$key] = $value;
    }

    /**
     * Removes the given key from the current collection.
     *
     * @param string $key The given key.
     *
     * @return void
     */
    public function delete(string $key): void
    {
        if ($this->has($key)) {
            unset($this->elements[$key]);
        }
    }

    /**
     * Clears all elements of the current collection.
     *
     * @return self
     */
    public function clear()
    {
        $this->elements = [];

        return $this;
    }

    /**
     * Gets an iterator instance of the current collection.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->elements);
    }
}
