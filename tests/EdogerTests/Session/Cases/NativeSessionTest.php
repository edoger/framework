<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Session\Cases;

use PHPUnit\Framework\TestCase;
use Edoger\Session\NativeSession;
use Edoger\Session\AbstractSession;
use Edoger\Session\Stores\GlobalSessionStore;
use EdogerTests\Session\Mocks\TestSessionHandler;

class NativeSessionTest extends TestCase
{
    public function testNativeSessionExtendsAbstractSession()
    {
        $session = new NativeSession(new TestSessionHandler());

        $this->assertInstanceOf(AbstractSession::class, $session);
    }

    public function testNativeSessionUsedGlobalSessionStore()
    {
        $store = (new NativeSession(new TestSessionHandler()))->getSessionStore();

        $this->assertInstanceOf(GlobalSessionStore::class, $store);
    }
}
