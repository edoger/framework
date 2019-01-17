<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Foundation;

use Edoger\Util\Arr;
use Edoger\Container\Collection as CollectionContainer;

class Collection extends CollectionContainer
{
    /**
     * Determines whether the current collection is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    /**
     * Determines whether a given key exists in the current collection.
     * If any of the keys in the given key is present in the current collection, it returns true
     * and no longer tests whether the other key exists in the current collection.
     *
     * @param iterable $keys The given multiple keys.
     *
     * @return bool
     */
    public function hasAny(iterable $keys): bool
    {
        return !$this->isEmpty() && Arr::hasAny($this->elements, $keys);
    }

    /**
     * Gets the value of any given key from the current collection.
     *
     * @param iterable $keys    The given multiple keys.
     * @param mixed    $default The default value.
     *
     * @return mixed
     */
    public function getAny(iterable $keys, $default = null)
    {
        if ($this->isEmpty()) {
            return $default;
        }

        return Arr::getAny($this->elements, $keys, $default);
    }

    /**
     * Replaces all elements in the current collection.
     *
     * @param mixed $elements The collection elements.
     *
     * @return self
     */
    public function replace($elements): self
    {
        $this->elements = Arr::convert($elements);

        return $this;
    }
}
