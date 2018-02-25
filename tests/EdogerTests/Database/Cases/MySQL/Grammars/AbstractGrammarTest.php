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
use Edoger\Database\MySQL\Arguments;
use Edoger\Database\MySQL\TcpServer;
use Edoger\Database\MySQL\Connection;
use EdogerTests\Database\Mocks\TestGrammar;
use Edoger\Database\MySQL\Grammars\SQLStatement;
use Edoger\Database\MySQL\Grammars\AbstractGrammar;

class AbstractGrammarTest extends TestCase
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

    protected function createTestGrammar()
    {
        return new TestGrammar($this->database, $this->table);
    }

    public function testTestGrammarExtendsAbstractGrammar()
    {
        $grammar = $this->createTestGrammar();

        $this->assertInstanceOf(TestGrammar::class, $grammar);
        $this->assertInstanceOf(AbstractGrammar::class, $grammar);
    }

    public function testAbstractGrammarCreate()
    {
        $grammar = TestGrammar::create($this->database, $this->table);

        $this->assertInstanceOf(AbstractGrammar::class, $grammar);
        $this->assertInstanceOf(TestGrammar::class, $grammar);
    }

    public function testAbstractGrammarCreateFromGrammar()
    {
        $grammar = TestGrammar::createFromGrammar($this->createTestGrammar());

        $this->assertInstanceOf(AbstractGrammar::class, $grammar);
        $this->assertInstanceOf(TestGrammar::class, $grammar);
    }

    public function testAbstractGrammarGetDatabase()
    {
        $grammar = $this->createTestGrammar();

        $this->assertEquals($this->database, $grammar->getDatabase());
    }

    public function testAbstractGrammarGetTable()
    {
        $grammar = $this->createTestGrammar();

        $this->assertEquals($this->table, $grammar->getTable());
    }

    public function testAbstractGrammarGetWrappedFullTableName()
    {
        $grammar = $this->createTestGrammar();

        $this->assertEquals('`edoger`.`users`', $grammar->getWrappedFullTableName());
    }

    public function testTestGrammarCompile()
    {
        $grammar   = $this->createTestGrammar();
        $arguments = Arguments::create('foo');

        $this->assertInstanceOf(SQLStatement::class, $grammar->compile());
        $this->assertInstanceOf(SQLStatement::class, $grammar->compile($arguments));
        $this->assertEquals($arguments, $grammar->compile($arguments)->getArguments());
    }
}
