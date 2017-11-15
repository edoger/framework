<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Foundation;

use Edoger\Container\Collection as CollectionContainer;

class Collection extends CollectionContainer
{
    /**
     * Determines whether a given key exists in the current collection.
     * If any of the keys in the given key is present in the current collection, it returns true
     * and no longer tests whether the other key exists in the current collection.
     *
     * @param  iterable  $keys The given multiple keys.
     * @return boolean
     */
    public function hasAny(iterable $keys): bool
    {
        foreach ($keys as $key) {
            if ($this->has($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the value of any given key from the current collection.
     *
     * @param  iterable $keys    The given multiple keys.
     * @param  mixed    $default The default value.
     * @return mixed
     */
    public function getAny(iterable $keys, $default = null)
    {
        foreach ($keys as $key) {
            if ($this->has($key)) {
                return $this->get($key);
            }
        }

        return $default;
    }
}
