<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Session\Stores;

use Edoger\Util\Arr;
use Edoger\Session\Contracts\SessionStore;

class GlobalSessionStore implements SessionStore
{
    /**
     * The session global store constructor.
     *
     * @return void
     */
    public function __construct()
    {
        // do nothing
    }

    /**
     * Determine if the current session data is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($_SESSION);
    }

    /**
     * Determines if the given session data key exists.
     *
     * @param string $key The given session data key.
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($_SESSION) && Arr::has($_SESSION, $key);
    }

    /**
     * Gets the value of the given session data key.
     *
     * @param string $key     The given session data key.
     * @param mixed  $default The default value.
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return isset($_SESSION) ? Arr::get($_SESSION, $key, $default) : $default;
    }

    /**
     * Get all session data.
     *
     * @return array
     */
    public function getAll(): array
    {
        return $_SESSION ?? [];
    }

    /**
     * Set a session data.
     *
     * @param string $key   The session data key.
     * @param mixed  $value The session data value.
     *
     * @return void
     */
    public function set(string $key, $value): void
    {
        if (isset($_SESSION)) {
            $_SESSION[$key] = $value;
        }
    }

    /**
     * Delete the session data for a given key name.
     *
     * @param string $key The given session data key.
     *
     * @return void
     */
    public function delete(string $key): void
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Clear all the current session data.
     *
     * @return void
     */
    public function clear(): void
    {
        if (isset($_SESSION)) {
            $_SESSION = [];
        }
    }
}
