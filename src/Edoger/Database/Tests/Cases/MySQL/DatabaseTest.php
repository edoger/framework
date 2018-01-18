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
use Edoger\Database\MySQL\Actuator;
use Edoger\Database\MySQL\Database;
use Edoger\Database\MySQL\TcpServer;
use Edoger\Database\MySQL\Connection;

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

    public function testDatabaseGetActuator()
    {
        $this->actuator->execute('USE edoger');

        $database = new Database($this->actuator);

        $this->assertEquals($this->actuator, $database->getActuator());
    }
}
