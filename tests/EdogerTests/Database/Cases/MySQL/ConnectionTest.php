<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL;

use PDO;
use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\TcpServer;
use Edoger\Database\MySQL\Connection;
use Edoger\Database\Exceptions\ConnectionException;
use Edoger\Database\MySQL\Contracts\Connection as ConnectionContract;

class ConnectionTest extends TestCase
{
    protected $server;

    protected function setUp()
    {
        if (defined('TEST_MYSQL_USERNAME') && defined('TEST_MYSQL_USERNAME')) {
            $this->server = new TcpServer('test', '127.0.0.1', 3306, TEST_MYSQL_USERNAME, TEST_MYSQL_USERNAME);
        } else {
            $this->markTestSkipped('Can not find system test MySQL account.');
        }
    }

    protected function tearDown()
    {
        $this->server = null;
    }

    public function testConnectionInstanceOfConnectionContract()
    {
        $connection = new Connection($this->server);

        $this->assertInstanceOf(ConnectionContract::class, $connection);
    }

    public function testConnectionGetServer()
    {
        $connection = new Connection($this->server);

        $this->assertEquals($this->server, $connection->getServer());
    }

    public function testConnectionGetName()
    {
        $connection = new Connection($this->server);

        $this->assertEquals('test', $connection->getName());
    }

    public function testConnectionIsConnected()
    {
        $connection = new Connection($this->server);

        $this->assertFalse($connection->isConnected());
        $connection->connect();
        $this->assertTrue($connection->isConnected());
        $connection->close();
        $this->assertFalse($connection->isConnected());
    }

    public function testConnectionConnect()
    {
        $connection = new Connection($this->server);

        $this->assertInstanceOf(PDO::class, $connection->connect());
    }

    public function testConnectionConnectFail()
    {
        $this->expectException(ConnectionException::class);

        $connection = new Connection(
            new TcpServer('test', '127.0.0.1', 3306, TEST_MYSQL_USERNAME, '')
        );

        $connection->connect(); // exception
    }

    public function testConnectionClose()
    {
        $connection = new Connection($this->server);

        $connection->connect();

        $this->assertTrue($connection->close());
    }
}
