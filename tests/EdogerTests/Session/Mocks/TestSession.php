<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Session\Mocks;

use Edoger\Session\AbstractSession;

class TestSession extends AbstractSession
{
    public function start(string $sessionId = ''): bool
    {
        $this->sessionId = $sessionId;

        return true;
    }
}
