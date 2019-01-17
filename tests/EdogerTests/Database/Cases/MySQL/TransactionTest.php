<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL;

use PDO;
use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Actuator;
use Edoger\Database\MySQL\TcpServer;
use Edoger\Database\MySQL\Connection;
use Edoger\Database\MySQL\Transaction;

class TransactionTest extends TestCase
{
    protected $connection;

    protected function setUp()
    {
        if (defined('TEST_MYSQL_USERNAME') && defined('TEST_MYSQL_USERNAME')) {
            $this->connection = new Connection(
                new TcpServer('test', '127.0.0.1', 3306, TEST_MYSQL_USERNAME, TEST_MYSQL_USERNAME, 'edoger')
            );
        } else {
            $this->markTestSkipped('Can not find system test MySQL account.');
        }
    }

    protected function tearDown()
    {
        if ($this->connection && $this->connection->connect()->inTransaction()) {
            $this->connection->connect()->rollBack();
        }

        $this->connection = null;
    }

    public function testTransactionStatus()
    {
        $transaction = new Transaction($this->connection);

        $this->assertFalse($transaction->status());

        $this->connection->connect();
        $this->assertFalse($transaction->status());

        $this->connection->connect()->beginTransaction();
        $this->assertTrue($transaction->status());

        $this->connection->connect()->commit();
        $this->assertFalse($transaction->status());

        $this->connection->connect()->beginTransaction();
        $this->assertTrue($transaction->status());

        $this->connection->connect()->rollBack();
        $this->assertFalse($transaction->status());
    }

    public function testTransactionOpen()
    {
        $transaction = new Transaction($this->connection);

        $this->assertTrue($transaction->open());
        // Try to activate the transaction multiple times.
        $this->assertTrue($transaction->open());
        $this->assertTrue($transaction->open());
    }

    public function testTransactionCommit()
    {
        $transaction = new Transaction($this->connection);

        // No activated transaction.
        $this->assertFalse($transaction->commit());

        $transaction->open();
        $this->assertTrue($transaction->commit());
        // Try to submit multiple times.
        $this->assertFalse($transaction->commit());
        $this->assertFalse($transaction->commit());
    }

    public function testTransactionBack()
    {
        $transaction = new Transaction($this->connection);

        // No activated transaction.
        $this->assertFalse($transaction->back());

        $transaction->open();
        $this->assertTrue($transaction->back());
        // Try to roll back multiple times.
        $this->assertFalse($transaction->commit());
        $this->assertFalse($transaction->commit());
    }

    public function testTransactionTransact()
    {
        $transaction = new Transaction($this->connection);
        $actuator    = new Actuator($this->connection);

        // Clear and truncate the database table.
        $actuator->execute('TRUNCATE TABLE edoger.users');

        $this->assertTrue($transaction->transact(function () use ($actuator) {
            $actuator->execute(
                "INSERT INTO edoger.users (name, age, hobbies) VALUES ('Edoger', 5, 'Painting')"
            );

            return true;
        }));

        $this->assertEquals(
            [
                ['name' => 'Edoger', 'age' => '5', 'hobbies' => 'Painting'],
            ],
            $actuator
                ->query('SELECT name, age, hobbies FROM edoger.users')
                ->fetchAll(PDO::FETCH_ASSOC)
        );

        // Clear and truncate the database table.
        $actuator->execute('TRUNCATE TABLE edoger.users');

        $this->assertFalse($transaction->transact(function () use ($actuator) {
            $actuator->execute(
                "INSERT INTO edoger.users (name, age, hobbies) VALUES ('Edoger', 5, 'Painting')"
            );

            return false;
        }));

        $this->assertEquals(
            [],
            $actuator
                ->query('SELECT name, age, hobbies FROM edoger.users')
                ->fetchAll(PDO::FETCH_ASSOC)
        );

        // Use option.
        $transaction->transact(function ($option) {
            $this->assertEquals('foo', $option);

            return false;
        }, 'foo');
    }
}
