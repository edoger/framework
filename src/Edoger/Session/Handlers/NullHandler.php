<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Session\Handlers;

use Edoger\Session\Contracts\SessionHandler;

class NullHandler implements SessionHandler
{
    /**
     * The session null handler constructor.
     *
     * @return void
     */
    public function __construct()
    {
        // do nothing
    }

    /**
     * Closes the current session.
     *
     * @return bool
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * Destroys a session.
     *
     * @param string $sessionId The session id.
     *
     * @return bool
     */
    public function destroy($sessionId): bool
    {
        return true;
    }

    /**
     * Cleans up expired sessions.
     *
     * @param int $maxLifeTime Sessions max life time.
     *
     * @return bool
     */
    public function gc($maxLifeTime): bool
    {
        return true;
    }

    /**
     * Re-initialize existing session, or creates a new one.
     *
     * @param string $savePath    The path where to store/retrieve the session.
     * @param string $sessionName The session name.
     *
     * @return bool
     */
    public function open($savePath, $sessionName): bool
    {
        return true;
    }

    /**
     * Reads the session data from the session storage, and returns the results.
     *
     * @param string $sessionId The session id.
     *
     * @return string
     */
    public function read($sessionId): string
    {
        return '';
    }

    /**
     * Writes the session data to the session storage.
     *
     * @param string $sessionId   The session id.
     * @param string $sessionData The encoded session data.
     *
     * @return bool
     */
    public function write($sessionId, $sessionData): bool
    {
        return true;
    }
}
