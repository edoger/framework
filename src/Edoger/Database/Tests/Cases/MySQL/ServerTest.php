<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\Tests\Cases\MySQL;

use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Server;
use Edoger\Database\Contracts\Server as ServerContract;

class ServerTest extends TestCase
{
    public function testServerInstanceOfServerContract()
    {
        $server = new Server('test');

        $this->assertInstanceOf(ServerContract::class, $server);
    }

    public function testServerGetName()
    {
        $server = new Server('test');

        $this->assertEquals('test', $server->getName());
    }

    public function testServerGetHost()
    {
        $server = new Server('test');
        $this->assertEquals('127.0.0.1', $server->getHost());

        $server = new Server('test', 'test.com');
        $this->assertEquals('test.com', $server->getHost());
    }

    public function testServerGetPort()
    {
        $server = new Server('test');
        $this->assertEquals(3306, $server->getPort());

        $server = new Server('test', '', 3000);
        $this->assertEquals(3000, $server->getPort());
    }

    public function testServerGetUnixSocketPath()
    {
        $server = new Server('test');
        $this->assertEquals('', $server->getUnixSocketPath());

        $server = new Server('test', '', 0, '/tmp/mysql.sock');
        $this->assertEquals('/tmp/mysql.sock', $server->getUnixSocketPath());
    }

    public function testServerGetUserName()
    {
        $server = new Server('test');
        $this->assertEquals('root', $server->getUserName());

        $server = new Server('test', '', 0, '', 'test');
        $this->assertEquals('test', $server->getUserName());
    }

    public function testServerGetPassword()
    {
        $server = new Server('test');
        $this->assertEquals('', $server->getPassword());

        $server = new Server('test', '', 0, '', '', 'test');
        $this->assertEquals('test', $server->getPassword());
    }

    public function testServerGetDatabaseName()
    {
        $server = new Server('test');
        $this->assertEquals('', $server->getDatabaseName());

        $server = new Server('test', '', 0, '', '', '', 'test');
        $this->assertEquals('test', $server->getDatabaseName());
    }

    public function testServerGetCharset()
    {
        $server = new Server('test');
        $this->assertEquals('utf8mb4', $server->getCharset());

        $server = new Server('test', '', 0, '', '', '', '', 'utf8');
        $this->assertEquals('utf8', $server->getCharset());
    }

    public function testServerGenerateDsn()
    {
        $server = new Server('test');
        $this->assertEquals('mysql:host=127.0.0.1;port=3306;charset=utf8mb4', $server->generateDsn());

        $server = new Server('test', '127.0.0.1', 3306, '/tmp/mysql.sock');
        $this->assertEquals('mysql:unix_socket=/tmp/mysql.sock;charset=utf8mb4', $server->generateDsn());

        $server = new Server('test', '127.0.0.1', 3306, '', 'root', 'password', 'testdb', 'utf8');
        $this->assertEquals('mysql:host=127.0.0.1;port=3306;dbname=testdb;charset=utf8', $server->generateDsn());
    }
}
