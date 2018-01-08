<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config;

use Countable;
use Edoger\Util\Arr;
use Edoger\Util\Contracts\Arrayable;

class Repository implements Arrayable, Countable
{
    /**
     * All configuration items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * The configuration repository constructor.
     *
     * @param iterable $items The configuration items.
     *
     * @return void
     */
    public function __construct(iterable $items = [])
    {
        foreach ($items as $key => $value) {
            $this->items[$key] = $value;
        }
    }

    /**
     * Determines whether the current configuration item repository is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Determines whether a configuration item for a given search string exists.
     *
     * @param string $search The configuration item search string.
     *
     * @return bool
     */
    public function has(string $search): bool
    {
        if ($this->isEmpty()) {
            return false;
        }

        $range = $this->items;

        foreach (explode('.', $search) as $unit) {
            if (is_array($range) && Arr::has($range, $unit)) {
                $range = $range[$unit];
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * Gets the configuration item for the given query string from the current repository.
     *
     * @param string $search  The configuration item search string.
     * @param mixed  $default The default value.
     *
     * @return mixed
     */
    public function get(string $search, $default = null)
    {
        if ($this->isEmpty()) {
            return $default;
        }

        $range = $this->items;

        foreach (explode('.', $search) as $unit) {
            if (is_array($range) && Arr::has($range, $unit)) {
                $range = $range[$unit];
            } else {
                return $default;
            }
        }

        return $range;
    }

    /**
     * Returns the current repository as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * Gets the size of the current repository.
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }
}
