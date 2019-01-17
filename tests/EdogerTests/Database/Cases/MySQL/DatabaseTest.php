<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Actuator;
use Edoger\Database\MySQL\Database;
use Edoger\Database\MySQL\TcpServer;
use Edoger\Database\MySQL\Connection;
use Edoger\Database\MySQL\Transaction;

class DatabaseTest extends TestCase
{
    protected $actuator;

    protected function setUp()
    {
        if (defined('TEST_MYSQL_USERNAME') && defined('TEST_MYSQL_USERNAME')) {
            $this->actuator = new Actuator(
                new Connection(
                    new TcpServer('test', '127.0.0.1', 3306, TEST_MYSQL_USERNAME, TEST_MYSQL_USERNAME)
                )
            );
        } else {
            $this->markTestSkipped('Can not find system test MySQL account.');
        }
    }

    protected function tearDown()
    {
        $this->actuator = null;
    }

    public function testDatabaseConstructorFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to determine the database name.');

        $this->actuator->getConnection()->connect();

        new Database($this->actuator); // exception
    }

    public function testDatabaseGetDatabaseName()
    {
        $this->actuator->execute('USE edoger');

        $database = new Database($this->actuator);

        $this->assertEquals('edoger', $database->getDatabaseName());
    }

    public function testDatabaseGetWrappedDatabaseName()
    {
        $this->actuator->execute('USE edoger');

        $database = new Database($this->actuator);

        $this->assertEquals('`edoger`', $database->getWrappedDatabaseName());
    }

    public function testDatabaseGetActuator()
    {
        $this->actuator->execute('USE edoger');

        $database = new Database($this->actuator);

        $this->assertEquals($this->actuator, $database->getActuator());
    }

    public function testDatabaseGetDatabaseNameFromConnection()
    {
        $database = new Database($this->actuator, 'edoger');

        $this->assertEquals('', $database->getDatabaseNameFromConnection());
        $this->actuator->execute('USE edoger');
        $this->assertEquals('edoger', $database->getDatabaseNameFromConnection());
    }

    public function testDatabaseUseDatabaseName()
    {
        $database = new Database($this->actuator, 'edoger');

        $this->assertEquals('', $database->getDatabaseNameFromConnection());
        $this->assertEquals($database, $database->useDatabaseName());
        $this->assertEquals('edoger', $database->getDatabaseNameFromConnection());

        $database->useDatabaseName('mysql');
        $this->assertEquals('mysql', $database->getDatabaseNameFromConnection());
    }

    public function testDatabaseGetDatabaseTables()
    {
        $database = new Database($this->actuator, 'edoger');

        $this->assertEquals(['users'], $database->getDatabaseTables());
    }

    public function testDatabaseGetTransaction()
    {
        $database = new Database($this->actuator, 'edoger');

        $this->assertInstanceOf(Transaction::class, $database->getTransaction());
    }
}
