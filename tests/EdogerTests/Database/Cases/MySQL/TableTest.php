<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL;

use Countable;
use RuntimeException;
use IteratorAggregate;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Table;
use Edoger\Database\MySQL\Actuator;
use Edoger\Database\MySQL\Database;
use Edoger\Database\MySQL\TcpServer;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Database\MySQL\Connection;

class TableTest extends TestCase
{
    protected $actuator;
    protected $database;

    protected function setUp()
    {
        if (defined('TEST_MYSQL_USERNAME') && defined('TEST_MYSQL_USERNAME')) {
            $this->actuator = new Actuator(
                new Connection(
                    new TcpServer('test', '127.0.0.1', 3306, TEST_MYSQL_USERNAME, TEST_MYSQL_USERNAME)
                )
            );
            $this->database = new Database($this->actuator, 'edoger');
        } else {
            $this->markTestSkipped('Can not find system test MySQL account.');
        }
    }

    protected function tearDown()
    {
        if ($this->actuator) {
            $this->actuator->execute('DROP TABLE IF EXISTS `edoger`.`test_no_primary_key_table`');
        }

        $this->actuator = null;
        $this->database = null;
    }

    public function testTableConstructorFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The database table name can not be empty.');

        new Table(''); // exception
    }

    public function testTableInstanceOfArrayable()
    {
        $table = new Table('users');

        $this->assertInstanceOf(Arrayable::class, $table);
    }

    public function testTableInstanceOfCountable()
    {
        $table = new Table('users');

        $this->assertInstanceOf(Countable::class, $table);
    }

    public function testTableInstanceOfIteratorAggregate()
    {
        $table = new Table('users');

        $this->assertInstanceOf(IteratorAggregate::class, $table);
    }

    public function testTableGetName()
    {
        $table = new Table('users');

        $this->assertEquals('users', $table->getName());
    }

    public function testTableGetWrappedName()
    {
        $table = new Table('users');

        $this->assertEquals('`users`', $table->getWrappedName());
    }

    public function testTableGetPrimaryKey()
    {
        $table = new Table('users');
        $this->assertEquals('id', $table->getPrimaryKey());

        $table = new Table('users', 'tid');
        $this->assertEquals('tid', $table->getPrimaryKey());
    }

    public function testTableGetWrappedPrimaryKey()
    {
        $table = new Table('users');
        $this->assertEquals('`id`', $table->getWrappedPrimaryKey());

        $table = new Table('users', 'tid');
        $this->assertEquals('`tid`', $table->getWrappedPrimaryKey());
    }

    public function testTableSetPrimaryKey()
    {
        $table = new Table('users');

        $this->assertEquals('id', $table->getPrimaryKey());
        $this->assertEquals($table, $table->setPrimaryKey('tid'));
        $this->assertEquals('tid', $table->getPrimaryKey());
    }

    public function testTableSetPrimaryKeyFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The database table primary key name can not be empty.');

        $table = new Table('users');

        $table->setPrimaryKey(''); // exception
    }

    public function testTableIsEmptyFields()
    {
        $table = new Table('users');
        $this->assertTrue($table->isEmptyFields());

        $table = new Table('users', 'id', ['name']);
        $this->assertFalse($table->isEmptyFields());
    }

    public function testTableGetFields()
    {
        $table = new Table('users');
        $this->assertEquals([], $table->getFields());

        $table = new Table('users', 'id', ['name']);
        $this->assertEquals(['name', 'id'], $table->getFields());
    }

    public function testTableGetWrappedFields()
    {
        $table = new Table('users');
        $this->assertEquals([], $table->getWrappedFields());

        $table = new Table('users', 'id', ['name']);
        $this->assertEquals(['`name`', '`id`'], $table->getWrappedFields());
    }

    public function testTableSetFields()
    {
        $table = new Table('users');

        $this->assertEquals([], $table->getFields());
        $this->assertEquals($table, $table->setFields(['name', 'id', 'age']));
        $this->assertEquals(['name', 'id', 'age'], $table->getFields());
        $this->assertEquals($table, $table->setFields([]));
        $this->assertEquals([], $table->getFields());
    }

    public function testTableSetFieldsFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid database table field.');

        $table = new Table('users');

        $table->setFields(['']); // exception
    }

    public function testTableFromDatabase()
    {
        $table = new Table('users', 'test', ['foo', 'bar', 'baz']);

        $this->assertEquals('test', $table->getPrimaryKey());
        $this->assertEquals(['foo', 'bar', 'baz', 'test'], $table->getFields());

        $this->assertEquals($table, $table->fromDatabase($this->database));

        $this->assertEquals('id', $table->getPrimaryKey());
        $this->assertEquals(['name', 'age', 'hobbies', 'id'], $table->getFields());
    }

    public function testTableFromDatabaseFailPart1()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The table "non" does not exist in database "edoger".');

        $table = new Table('non');

        $table->fromDatabase($this->database); // exception
    }

    public function testTableFromDatabaseFailPart2()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'The table "test_no_primary_key_table" primary key column does not exist in database "edoger".'
        );

        $this->actuator->execute('DROP TABLE IF EXISTS `edoger`.`test_no_primary_key_table`');
        $this->actuator->execute(
            'CREATE TABLE `edoger`.`test_no_primary_key_table` (`foo` int(11) DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );

        $table = new Table('test_no_primary_key_table');

        $table->fromDatabase($this->database); // exception
    }

    public function testTableArrayable()
    {
        $table = new Table('users');

        $this->assertEquals([], $table->toArray());

        $table->setFields(['name', 'id', 'age']);

        $this->assertEquals(['name' => '`name`', 'id' => '`id`', 'age' => '`age`'], $table->toArray());
    }

    public function testTableCountable()
    {
        $table = new Table('users');

        $this->assertEquals(0, count($table));

        $table->setFields(['name', 'id', 'age']);

        $this->assertEquals(3, count($table));
    }

    public function testTableIteratorAggregate()
    {
        $table  = new Table('users', 'id', ['name', 'id', 'age']);
        $fields = ['name' => '`name`', 'id' => '`id`', 'age' => '`age`'];

        foreach ($table as $key => $value) {
            $this->assertEquals($fields[$key], $value);
        }
    }
}
