<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Cache\Drivers;

use Edoger\Util\Arr;
use Edoger\Cache\Contracts\Driver;

class ArrayDriver implements Driver
{
    /**
     * The cache data.
     *
     * @var array
     */
    protected $cacheData;

    /**
     * The PHP apcu cache driver constructor.
     *
     * @param array $cacheData The cache data.
     *
     * @return void
     */
    public function __construct(array $cacheData = [])
    {
        $this->cacheData = $cacheData;
    }

    /**
     * Determine if the current driver is enabled.
     *
     * @return bool
     */
    public static function isEnabled(): bool
    {
        return true;
    }

    /**
     * Determines whether an item is present in the cache.
     *
     * @param string $key The cache item key.
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return Arr::has($this->cacheData, $key);
    }

    /**
     * Fetches a value from the cache.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default Default value to return if the key does not exist.
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return Arr::get($this->cacheData, $key, $default);
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string $key   The key of the item to store.
     * @param mixed  $value The value of the item to store, must be serializable.
     * @param int    $ttl   The TTL value of this item. (Not applicable)
     *
     * @return bool
     */
    public function set(string $key, $value, int $ttl = 0): bool
    {
        $this->cacheData[$key] = $value;

        return true;
    }

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     *
     * @return bool
     */
    public function delete(string $key): bool
    {
        if ($this->has($key)) {
            unset($this->cacheData[$key]);
        }

        return true;
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool
     */
    public function clear(): bool
    {
        $this->cacheData = [];

        return true;
    }
}
