<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\Tests\Cases\MySQL;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Server;
use Edoger\Database\MySQL\SocketServer;

class SocketServerTest extends TestCase
{
    public function testSocketServerExtendsServer()
    {
        $server = new SocketServer('test', '/tmp/mysql.sock');

        $this->assertInstanceOf(Server::class, $server);
    }

    public function testSocketServerConstructorFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid MySQL unix socket path.');

        new SocketServer('test', ''); // exception
    }

    public function testSocketServerGenerateDsn()
    {
        $server = new SocketServer('test', '/tmp/mysql.sock');

        $this->assertEquals('mysql:unix_socket=/tmp/mysql.sock;charset=utf8mb4', $server->generateDsn());
    }
}
