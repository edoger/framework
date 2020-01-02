<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Http\Cases\Server\Globals;

use PHPUnit\Framework\TestCase;
use Edoger\Http\Foundation\Collection;
use Edoger\Http\Server\Globals\Server;

class ServerTest extends TestCase
{
    public function testServerExtendsCollection()
    {
        $server = new Server();

        $this->assertInstanceOf(Collection::class, $server);
    }

    public function testServerCreate()
    {
        $server = Server::create(['test' => 'test']);

        $this->assertInstanceOf(Collection::class, $server);
        $this->assertEquals(['test' => 'test'], $server->toArray());
    }
}
