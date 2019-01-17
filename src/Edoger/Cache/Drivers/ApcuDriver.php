<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Cache\Drivers;

use RuntimeException;
use Edoger\Util\Environment;
use Edoger\Cache\Contracts\Driver;

class ApcuDriver implements Driver
{
    /**
     * The PHP apcu cache driver constructor.
     *
     * @throws RuntimeException Thrown when the PHP apcu extension is not loaded.
     *
     * @return void
     */
    public function __construct()
    {
        if (!static::isEnabled()) {
            throw new RuntimeException('The "apcu" extension is not loaded or not enabled.');
        }

        // Not use the SAPI request start time for TTL.
        if (Environment::isCli()) {
            ini_set('apc.use_request_time', 0);
        }
    }

    /**
     * Determine if the current driver is enabled.
     *
     * @return bool
     */
    public static function isEnabled(): bool
    {
        if (extension_loaded('apcu') && ini_get('apc.enabled')) {
            // In CLI mode, the "apc.enable_cli" option must be enabled.
            if (Environment::isCli()) {
                return (bool) ini_get('apc.enable_cli');
            }

            return true;
        }

        return false;
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
        return apcu_exists($key);
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
        if ($this->has($key)) {
            $value = apcu_fetch($key, $success);

            // If reading cached data fails, the default value will be returned.
            if ($success) {
                return $value;
            }
        }

        return $default;
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
        return apcu_store($key, $value, $ttl);
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
            return apcu_delete($key);
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
        return apcu_clear_cache();
    }
}
