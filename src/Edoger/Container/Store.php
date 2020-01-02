<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Container;

use Countable;
use ArrayIterator;
use Edoger\Util\Arr;
use IteratorAggregate;
use Edoger\Util\Contracts\Arrayable;

class Store implements Arrayable, Countable, IteratorAggregate
{
    /**
     * All the elements of the current store.
     *
     * @var array
     */
    protected $elements = [];

    /**
     * The store constructor.
     *
     * @param mixed $elements The elements that are added into the store.
     *
     * @return void
     */
    public function __construct($elements = [])
    {
        $this->elements = Arr::convert($elements);
    }

    /**
     * Determines whether the current store is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    /**
     * Appends an element to the current store and returns the length of the current store.
     *
     * @param mixed $element The given element.
     * @param bool  $top     Whether to append an element to the top of the store.
     *
     * @return int
     */
    public function append($element, bool $top = false): int
    {
        if ($top) {
            return array_unshift($this->elements, $element);
        } else {
            return array_push($this->elements, $element);
        }
    }

    /**
     * Remove an element from the current store and return it.
     * Returns NULL if the current store is empty.
     *
     * @param bool $top Whether to remove an element from the top of the current store.
     *
     * @return mixed
     */
    public function remove(bool $top = true)
    {
        if ($top) {
            return array_shift($this->elements);
        } else {
            return array_pop($this->elements);
        }
    }

    /**
     * Clear the current store.
     *
     * @return self
     */
    public function clear()
    {
        $this->elements = [];

        return $this;
    }

    /**
     * Returns the current store as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->elements;
    }

    /**
     * Gets the size of the current store.
     *
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * Gets an iterator instance of the current store.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->elements);
    }
}
