<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL\Grammars;

use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Table;
use Edoger\Database\MySQL\Actuator;
use Edoger\Database\MySQL\Database;
use Edoger\Database\MySQL\TcpServer;
use Edoger\Database\MySQL\Connection;
use Edoger\Database\MySQL\Grammars\Statement;
use Edoger\Database\MySQL\Grammars\InsertGrammar;
use Edoger\Database\MySQL\Grammars\AbstractGrammar;
use Edoger\Database\MySQL\Exceptions\GrammarException;
use Edoger\Database\MySQL\Grammars\StatementContainer;

class InsertGrammarTest extends TestCase
{
    protected $actuator;
    protected $database;
    protected $table;

    protected function setUp()
    {
        if (defined('TEST_MYSQL_USERNAME') && defined('TEST_MYSQL_USERNAME')) {
            $this->actuator = new Actuator(
                new Connection(
                    new TcpServer('test', '127.0.0.1', 3306, TEST_MYSQL_USERNAME, TEST_MYSQL_USERNAME)
                )
            );
            $this->database = new Database($this->actuator, 'edoger');
            $this->table    = new Table('users');
        } else {
            $this->markTestSkipped('Can not find system test MySQL account.');
        }
    }

    protected function tearDown()
    {
        $this->actuator = null;
        $this->database = null;
        $this->table    = null;
    }

    protected function createInsertGrammar()
    {
        return new InsertGrammar($this->database, $this->table);
    }

    public function testInsertGrammarExtendsAbstractGrammar()
    {
        $grammar = $this->createInsertGrammar();

        $this->assertInstanceOf(AbstractGrammar::class, $grammar);
    }

    public function testInsertGrammarHasColumnData()
    {
        $grammar = $this->createInsertGrammar();

        $this->assertFalse($grammar->hasColumnData());
        $grammar->setColumn('foo', 'foo');
        $this->assertTrue($grammar->hasColumnData());
    }

    public function testInsertGrammarGetColumnData()
    {
        $grammar = $this->createInsertGrammar();

        $this->assertEquals([], $grammar->getColumnData());
        $grammar->setColumn('foo', 'foo');
        $this->assertEquals(['foo' => ['foo']], $grammar->getColumnData());
    }

    public function testInsertGrammarSetColumn()
    {
        $grammar = $this->createInsertGrammar();

        $this->assertEquals($grammar, $grammar->setColumn('foo', 'foo'));
        $this->assertEquals(['foo' => ['foo']], $grammar->getColumnData());
        $this->assertEquals($grammar, $grammar->setColumn('foo', 'bar'));
        $this->assertEquals(['foo' => ['foo', 'bar']], $grammar->getColumnData());
        $this->assertEquals($grammar, $grammar->setColumn('bar', null));
        $this->assertEquals(['foo' => ['foo', 'bar'], 'bar' => [null]], $grammar->getColumnData());
    }

    public function testInsertGrammarSetColumns()
    {
        $grammar = $this->createInsertGrammar();

        $this->assertEquals($grammar, $grammar->setColumns(['foo' => 'foo', 'bar' => null, 'baz' => [1, 2]]));
        $this->assertEquals(['foo' => ['foo'], 'bar' => [null], 'baz' => [1, 2]], $grammar->getColumnData());
    }

    public function testInsertGrammarCompile()
    {
        $grammar = $this->createInsertGrammar();

        $grammar->setColumn('foo', 'foo');
        $grammar->setColumn('bar', null);
        $grammar->setColumn('baz', [1, 2, 3]);

        $container = $grammar->compile();
        $this->assertInstanceOf(StatementContainer::class, $container);
        $this->assertEquals(3, count($container));

        $sql    = 'INSERT INTO `edoger`.`users` (`foo`,`bar`,`baz`) VALUES (?,?,?)';
        $values = [['foo', '', 1], ['foo', '', 2], ['foo', '', 3]];

        foreach ($values as $value) {
            $statement = $container->pop();

            $this->assertInstanceOf(Statement::class, $statement);
            $this->assertEquals($value, $statement->getArguments()->toArray());
            $this->assertEquals($sql, $statement->getStatement());
        }
    }

    public function testInsertGrammarCompileFailByEmptyData()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('The insert column data can not be empty.');

        $this->createInsertGrammar()->compile(); // exception
    }

    public function testInsertGrammarCompileFailByInvalidColumn()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('The data column "foo" does not exist or is not allowed to be written.');

        $this->table->setFields(['bar']);

        $grammar = $this->createInsertGrammar();

        $grammar->setColumn('foo', 'foo');
        $grammar->compile(); // exception
    }
}
