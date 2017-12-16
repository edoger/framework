<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Session\Stores;

use Edoger\Util\Arr;
use Edoger\Session\Contracts\SessionStore;

class ArraySessionStore implements SessionStore
{
    /**
     * The session data.
     *
     * @var array
     */
    protected $sessionData;

    /**
     * The session global store constructor.
     *
     * @param array $sessionData The session data.
     *
     * @return void
     */
    public function __construct(array $sessionData = [])
    {
        $this->sessionData = $sessionData;
    }

    /**
     * Determine if the current session data is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->sessionData);
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
        return Arr::has($this->sessionData, $key);
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
        return Arr::get($this->sessionData, $key, $default);
    }

    /**
     * Get all session data.
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->sessionData;
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
        $this->sessionData[$key] = $value;
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
            unset($this->sessionData[$key]);
        }
    }

    /**
     * Clear all the current session data.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->sessionData = [];
    }
}
