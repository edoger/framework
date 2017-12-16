<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Session\Tests\Support;

use Edoger\Session\AbstractSession;

class TestSession extends AbstractSession
{
    public function start(string $sessionId = ''): bool
    {
        $this->sessionId = $sessionId;

        return true;
    }
}
