<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Cache;

use stdClass;
use InvalidArgumentException;
use Edoger\Cache\Contracts\Driver;

class Cache
{
    /**
     * The cache driver.
     *
     * @var Edoger\Cache\Contracts\Driver
     */
    protected $driver;

    /**
     * The cache key name prefix.
     *
     * @var string
     */
    protected $prefix;

    /**
     * The cache constructor.
     *
     * @param Edoger\Cache\Contracts\Driver $driver The cache driver instance.
     * @param string                        $prefix The cache key name prefix.
     *
     * @return void
     */
    public function __construct(Driver $driver, string $prefix = 'edoger::cache::')
    {
        $this->driver = $driver;
        $this->setPrefix($prefix);
    }

    /**
     * Get the cache driver.
     *
     * @return Edoger\Cache\Contracts\Driver
     */
    public function getDriver(): Driver
    {
        return $this->driver;
    }

    /**
     * Get the cache key name prefix.
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Set the cache key name prefix.
     *
     * @param string $prefix The cache key name prefix.
     *
     * @return void
     */
    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
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
        return $this->getDriver()->has($this->prefix.$key);
    }

    /**
     * Determines if any of the given keys exist in the cache.
     *
     * @param iterable $keys A list of cache item keys.
     *
     * @throws InvalidArgumentException Throws when the cache key is invalid.
     *
     * @return bool
     */
    public function hasAny(iterable $keys): bool
    {
        foreach ($keys as $key) {
            if (is_string($key) || is_numeric($key)) {
                if ($this->has($key)) {
                    return true;
                }
            } else {
                throw new InvalidArgumentException('The cache key must be a string.');
            }
        }

        return false;
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
        return $this->getDriver()->get($this->prefix.$key, $default);
    }

    /**
     * [getAny description].
     *
     * @param iterable $keys    A list of cache item keys.
     * @param mixed    $default The default value.
     *
     * @throws InvalidArgumentException Throws when the cache key is invalid.
     *
     * @return mixed
     */
    public function getAny(iterable $keys, $default = null)
    {
        $scouter = new stdClass();

        foreach ($keys as $key) {
            if (is_string($key) || is_numeric($key)) {
                if ($scouter !== $value = $this->get($key, $scouter)) {
                    return $value;
                }
            } else {
                throw new InvalidArgumentException('The cache key must be a string.');
            }
        }

        return $default;
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys    A list of keys that can obtained in a single operation.
     * @param mixed    $default Default value to return for keys that do not exist.
     *
     * @throws InvalidArgumentException Throws when the cache key is invalid.
     *
     * @return array
     */
    public function getMultiple(iterable $keys, $default = null): array
    {
        $values = [];

        foreach ($keys as $key) {
            if (is_string($key) || is_numeric($key)) {
                $values[$key] = $this->get($key, $default);
            } else {
                throw new InvalidArgumentException('The cache key must be a string.');
            }
        }

        return $values;
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string $key   The key of the item to store.
     * @param mixed  $value The value of the item to store, must be serializable.
     * @param int    $ttl   The TTL value of this item.
     *
     * @return bool
     */
    public function set(string $key, $value, int $ttl = 0): bool
    {
        return $this->getDriver()->set($this->prefix.$key, $value, $ttl);
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable $values  A list of key => value pairs for a multiple-set operation.
     * @param int      $ttl     The TTL value of this item.
     * @param array    &$failed A list of failed cache items.
     *
     * @return bool
     */
    public function setMultiple(iterable $values, int $ttl = 0, array &$failed = null): bool
    {
        $failed = [];

        foreach ($values as $key => $value) {
            if (!$this->set($key, $value, $ttl)) {
                $failed[$key] = $value;
            }
        }

        return empty($failed);
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
        return $this->getDriver()->delete($this->prefix.$key);
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys    A list of string-based keys to be deleted.
     * @param array    &$failed The failed key list.
     *
     * @throws InvalidArgumentException Throws when the cache key is invalid.
     *
     * @return bool
     */
    public function deleteMultiple(iterable $keys, array &$failed = null): bool
    {
        $failed = [];

        foreach ($keys as $key) {
            if (is_string($key) || is_numeric($key)) {
                if (!$this->delete($key)) {
                    $failed[] = $key;
                }
            } else {
                throw new InvalidArgumentException('The cache key must be a string.');
            }
        }

        return empty($failed);
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool
     */
    public function clear(): bool
    {
        return $this->getDriver()->clear();
    }
}
