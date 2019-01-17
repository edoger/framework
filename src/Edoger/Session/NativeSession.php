<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Session;

use Edoger\Session\Contracts\SessionHandler;
use Edoger\Session\Stores\GlobalSessionStore;

class NativeSession extends AbstractSession
{
    /**
     * The session constructor.
     *
     * @param Edoger\Session\Contracts\SessionHandler $handler The session handler.
     *
     * @return void
     */
    public function __construct(SessionHandler $handler)
    {
        // By default, super-global variables are used.
        parent::__construct(new GlobalSessionStore(), $handler);
    }

    /**
     * Start the current session.
     *
     * @codeCoverageIgnore
     *
     * @param string $sessionId The session id.
     *
     * @return bool
     */
    public function start(string $sessionId = ''): bool
    {
        if ($this->isStarted()) {
            return true;
        }

        // If the session has already started, the existing session is automatically used.
        // This may be due to another program starting a session.
        // When there is an active session, if the global variables are deleted, the local session's
        // storage engine will not work properly.
        if (PHP_SESSION_ACTIVE === session_status()) {
            $this->sessionId = session_id();

            // This will check if the session id is empty.
            return $this->isStarted();
        }

        // If there is no session id, the system will automatically create a session id.
        if ('' === $sessionId) {
            $sessionId = session_create_id();
        }

        // Bind the session id.
        session_id($sessionId);

        // Set the session handler.
        // We only try to start the session if it is set up successfully.
        if (!session_set_save_handler($this->getSessionHandler(), true)) {
            return false;
        }

        // Attempt to start the session, while forbidding the system to automatically send
        // the session cookie header.
        if (session_start(['use_cookies' => 0])) {
            $this->sessionId = session_id();

            return true;
        }

        return false;
    }
}
