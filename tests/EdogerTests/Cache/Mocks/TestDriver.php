<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Cache\Mocks;

use Edoger\Cache\Contracts\Driver;

class TestDriver implements Driver
{
    protected $items = ['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz'];

    public function __construct(array $items = [])
    {
        if (!empty($items)) {
            $this->items = $items;
        }
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    public function get(string $key, $default = null)
    {
        return $this->has($key) ? $this->items[$key] : $default;
    }

    public function set(string $key, $value, int $ttl = 0): bool
    {
        if ($this->has($key)) {
            return false;
        }

        $this->items[$key] = $value;

        return true;
    }

    public function delete(string $key): bool
    {
        if ($this->has($key)) {
            unset($this->items[$key]);

            return true;
        }

        return false;
    }

    public function clear(): bool
    {
        if (empty($this->items)) {
            return false;
        }

        $this->items = [];

        return true;
    }
}
