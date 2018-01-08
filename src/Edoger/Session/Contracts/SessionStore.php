<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Session\Contracts;

interface SessionStore
{
    /**
     * Determine if the current session data is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Determines if the given session data key exists.
     *
     * @param string $key The given session data key.
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Gets the value of the given session data key.
     *
     * @param string $key     The given session data key.
     * @param mixed  $default The default value.
     *
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Get all session data.
     *
     * @return array
     */
    public function getAll(): array;

    /**
     * Set a session data.
     *
     * @param string $key   The session data key.
     * @param mixed  $value The session data value.
     *
     * @return void
     */
    public function set(string $key, $value): void;

    /**
     * Delete the session data for a given key name.
     *
     * @param string $key The given session data key.
     *
     * @return void
     */
    public function delete(string $key): void;

    /**
     * Clear all the current session data.
     *
     * @return void
     */
    public function clear(): void;
}
