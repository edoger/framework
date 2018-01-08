<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Container;

use Countable;
use Edoger\Util\Arr;
use Edoger\Util\Contracts\Arrayable;

class Container implements Arrayable, Countable
{
    /**
     * All the elements of the current container.
     *
     * @var array
     */
    protected $elements = [];

    /**
     * The container constructor.
     *
     * @param mixed $elements The elements that are added into the container.
     *
     * @return void
     */
    public function __construct($elements = [])
    {
        $this->elements = Arr::convert($elements);
    }

    /**
     * Determines whether the given key exists in the current container.
     *
     * @param string $key The given key.
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return Arr::has($this->elements, $key);
    }

    /**
     * Gets the value of the given key from the current container,
     * and returns the given default if the given key does not exist in the current container.
     *
     * @param string $key     The given key.
     * @param mixed  $default The default value.
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return Arr::get($this->elements, $key, $default);
    }

    /**
     * Returns the current container as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->elements;
    }

    /**
     * Gets the size of the current container.
     *
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }
}
