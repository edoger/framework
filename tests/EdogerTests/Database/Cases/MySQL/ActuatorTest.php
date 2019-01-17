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
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Actuator;
use Edoger\Database\MySQL\TcpServer;
use Edoger\Database\MySQL\Connection;
use Edoger\Database\MySQL\Exceptions\ExecutionException;

class ActuatorTest extends TestCase
{
    protected $connection;

    protected function setUp()
    {
        if (defined('TEST_MYSQL_USERNAME') && defined('TEST_MYSQL_USERNAME')) {
            $this->connection = new Connection(
                new TcpServer('test', '127.0.0.1', 3306, TEST_MYSQL_USERNAME, TEST_MYSQL_USERNAME)
            );

            $this->connection->connect()->exec('TRUNCATE TABLE edoger.users');
            $this->connection->connect()->exec(
                "INSERT INTO edoger.users (name, age, hobbies) VALUES ('Edoger', 5, 'Painting')"
            );
        } else {
            $this->markTestSkipped('Can not find system test MySQL account.');
        }
    }

    protected function tearDown()
    {
        if ($this->connection) {
            $this->connection->connect()->exec('TRUNCATE TABLE edoger.users');
        }

        $this->connection = null;
    }

    public function testActuatorGetConnection()
    {
        $actuator = new Actuator($this->connection);

        $this->assertEquals($this->connection, $actuator->getConnection());
    }

    public function testActuatorExecute()
    {
        $actuator  = new Actuator($this->connection);
        $statement = 'SELECT * FROM edoger.users';

        $this->assertEquals(
            [
                ['id' => '1', 'name' => 'Edoger', 'age' => '5', 'hobbies' => 'Painting'],
            ],
            $this->connection->connect()->query($statement)->fetchAll(PDO::FETCH_ASSOC)
        );

        $actuator->execute(
            "INSERT INTO edoger.users (name, age, hobbies) VALUES ('Tester', 10, 'Read')"
        );

        $this->assertEquals(
            [
                ['id' => '1', 'name' => 'Edoger', 'age' => '5', 'hobbies' => 'Painting'],
                ['id' => '2', 'name' => 'Tester', 'age' => '10', 'hobbies' => 'Read'],
            ],
            $this->connection->connect()->query($statement)->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function testActuatorExecuteFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The SQL statement can not be an empty string.');

        $actuator = new Actuator($this->connection);

        $actuator->execute(''); // exception
    }

    public function testActuatorQuery()
    {
        $actuator = new Actuator($this->connection);

        $this->assertEquals(
            [
                ['id' => '1', 'name' => 'Edoger', 'age' => '5', 'hobbies' => 'Painting'],
            ],
            $actuator
                ->query('SELECT * FROM edoger.users')
                ->fetchAll(PDO::FETCH_ASSOC)
        );

        $this->assertEquals(
            [
                ['id' => '1', 'name' => 'Edoger', 'age' => '5', 'hobbies' => 'Painting'],
            ],
            $actuator
                ->query('SELECT * FROM edoger.users WHERE id=?', [1])
                ->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function testActuatorQueryFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The SQL statement can not be an empty string.');

        $actuator = new Actuator($this->connection);

        $actuator->query(''); // exception
    }

    public function testActuatorUpdate()
    {
        $actuator = new Actuator($this->connection);

        $this->assertEquals(
            [
                ['id' => '1', 'name' => 'Edoger', 'age' => '5', 'hobbies' => 'Painting'],
            ],
            $actuator
                ->query('SELECT * FROM edoger.users')
                ->fetchAll(PDO::FETCH_ASSOC)
        );
        $this->assertEquals(1, $actuator->update("UPDATE edoger.users SET hobbies='Read' WHERE id=1"));
        $this->assertEquals(
            [
                ['id' => '1', 'name' => 'Edoger', 'age' => '5', 'hobbies' => 'Read'],
            ],
            $actuator
                ->query('SELECT * FROM edoger.users')
                ->fetchAll(PDO::FETCH_ASSOC)
        );
        $this->assertEquals(1, $actuator->update("UPDATE edoger.users SET hobbies='Painting' WHERE id=?", [1]));
        $this->assertEquals(
            [
                ['id' => '1', 'name' => 'Edoger', 'age' => '5', 'hobbies' => 'Painting'],
            ],
            $actuator
                ->query('SELECT * FROM edoger.users')
                ->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function testActuatorUpdateFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The SQL statement can not be an empty string.');

        $actuator = new Actuator($this->connection);

        $actuator->update(''); // exception
    }

    public function testActuatorInsert()
    {
        $actuator = new Actuator($this->connection);

        $this->assertEquals(
            [
                ['id' => '1', 'name' => 'Edoger', 'age' => '5', 'hobbies' => 'Painting'],
            ],
            $actuator->query('SELECT * FROM edoger.users')->fetchAll(PDO::FETCH_ASSOC)
        );
        $this->assertEquals('2', $actuator->insert(
            "INSERT INTO edoger.users (name, age, hobbies) VALUES ('Tester', 10, 'Read')"
        ));
        $this->assertEquals(
            [
                ['id' => '1', 'name' => 'Edoger', 'age' => '5', 'hobbies' => 'Painting'],
                ['id' => '2', 'name' => 'Tester', 'age' => '10', 'hobbies' => 'Read'],
            ],
            $actuator->query('SELECT * FROM edoger.users')->fetchAll(PDO::FETCH_ASSOC)
        );
        $this->assertEquals('3', $actuator->insert(
            'INSERT INTO edoger.users (name, age, hobbies) VALUES (?, ?, ?)',
            ['Actuator', 5, 'Writing']
        ));
        $this->assertEquals(
            [
                ['id' => '1', 'name' => 'Edoger', 'age' => '5', 'hobbies' => 'Painting'],
                ['id' => '2', 'name' => 'Tester', 'age' => '10', 'hobbies' => 'Read'],
                ['id' => '3', 'name' => 'Actuator', 'age' => '5', 'hobbies' => 'Writing'],
            ],
            $actuator
                ->query('SELECT * FROM edoger.users')
                ->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function testActuatorInsertFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The SQL statement can not be an empty string.');

        $actuator = new Actuator($this->connection);

        $actuator->insert(''); // exception
    }

    public function testActuatorDelete()
    {
        $actuator = new Actuator($this->connection);

        $actuator->insert(
            "INSERT INTO edoger.users (name, age, hobbies) VALUES ('Tester', 10, 'Read'),('Actuator', 5, 'Writing')"
        );

        $this->assertEquals(
            [
                ['id' => '1', 'name' => 'Edoger', 'age' => '5', 'hobbies' => 'Painting'],
                ['id' => '2', 'name' => 'Tester', 'age' => '10', 'hobbies' => 'Read'],
                ['id' => '3', 'name' => 'Actuator', 'age' => '5', 'hobbies' => 'Writing'],
            ],
            $actuator->query('SELECT * FROM edoger.users')->fetchAll(PDO::FETCH_ASSOC)
        );

        $this->assertEquals(1, $actuator->delete(
            'DELETE FROM edoger.users WHERE id=1'
        ));

        $this->assertEquals(
            [
                ['id' => '2', 'name' => 'Tester', 'age' => '10', 'hobbies' => 'Read'],
                ['id' => '3', 'name' => 'Actuator', 'age' => '5', 'hobbies' => 'Writing'],
            ],
            $actuator->query('SELECT * FROM edoger.users')->fetchAll(PDO::FETCH_ASSOC)
        );

        $this->assertEquals(2, $actuator->delete(
            'DELETE FROM edoger.users WHERE id IN (?, ?)',
            [2, 3]
        ));

        $this->assertEquals(
            [],
            $actuator->query('SELECT * FROM edoger.users')->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function testActuatorDeleteFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The SQL statement can not be an empty string.');

        $actuator = new Actuator($this->connection);

        $actuator->delete(''); // exception
    }

    public function testActuatorFail()
    {
        $this->expectException(ExecutionException::class);

        $actuator = new Actuator($this->connection);

        $actuator->query('THIS IS AN INVALID STATEMENT', [1]); // exception
    }
}
