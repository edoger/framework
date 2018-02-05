<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL;

use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Server;
use Edoger\Database\MySQL\TcpServer;

class TcpServerTest extends TestCase
{
    public function testTcpServerExtendsServer()
    {
        $server = new TcpServer('test');

        $this->assertInstanceOf(Server::class, $server);
    }

    public function testSocketServerGenerateDsn()
    {
        $server = new TcpServer('test');
        $this->assertEquals('mysql:host=127.0.0.1;port=3306;charset=utf8mb4', $server->generateDsn());

        $server = new TcpServer('test', 'test.com', 3000);
        $this->assertEquals('mysql:host=test.com;port=3000;charset=utf8mb4', $server->generateDsn());
    }
}
