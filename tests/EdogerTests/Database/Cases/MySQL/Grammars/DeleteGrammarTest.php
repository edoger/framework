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
use Edoger\Database\MySQL\Grammars\DeleteGrammar;
use Edoger\Database\MySQL\Grammars\AbstractGrammar;
use Edoger\Database\MySQL\Grammars\StatementContainer;
use Edoger\Database\MySQL\Grammars\Traits\LimitGrammarSupport;
use Edoger\Database\MySQL\Grammars\Traits\WhereGrammarSupport;
use Edoger\Database\MySQL\Grammars\Traits\WhereGrammarFoundationSupport;

class DeleteGrammarTest extends TestCase
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

    protected function createDeleteGrammar()
    {
        return new DeleteGrammar($this->database, $this->table);
    }

    public function testDeleteGrammarExtendsAbstractGrammar()
    {
        $grammar = $this->createDeleteGrammar();

        $this->assertInstanceOf(AbstractGrammar::class, $grammar);
    }

    public function testDeleteGrammarUseTraitWhereGrammarFoundationSupport()
    {
        $uses     = class_uses($this->createDeleteGrammar());
        $abstract = WhereGrammarFoundationSupport::class;

        $this->assertArrayHasKey($abstract, $uses);
        $this->assertEquals($abstract, $uses[$abstract]);
    }

    public function testDeleteGrammarUseTraitWhereGrammarSupport()
    {
        $uses     = class_uses($this->createDeleteGrammar());
        $abstract = WhereGrammarSupport::class;

        $this->assertArrayHasKey($abstract, $uses);
        $this->assertEquals($abstract, $uses[$abstract]);
    }

    public function testDeleteGrammarUseTraitLimitGrammarSupport()
    {
        $uses     = class_uses($this->createDeleteGrammar());
        $abstract = LimitGrammarSupport::class;

        $this->assertArrayHasKey($abstract, $uses);
        $this->assertEquals($abstract, $uses[$abstract]);
    }

    public function testDeleteGrammarCompile()
    {
        $grammar = $this->createDeleteGrammar();

        $container = $grammar->compile();
        $this->assertInstanceOf(StatementContainer::class, $container);
        $this->assertEquals(1, count($container));
        $statement = $container->pop();
        $this->assertInstanceOf(Statement::class, $statement);
        $this->assertEquals([], $statement->getArguments()->toArray());
        $this->assertEquals('DELETE FROM `edoger`.`users`', $statement->getStatement());

        $grammar->where('foo', 'foo');

        $container = $grammar->compile();
        $this->assertInstanceOf(StatementContainer::class, $container);
        $this->assertEquals(1, count($container));
        $statement = $container->pop();
        $this->assertInstanceOf(Statement::class, $statement);
        $this->assertEquals(['foo'], $statement->getArguments()->toArray());
        $this->assertEquals('DELETE FROM `edoger`.`users` WHERE `foo` = ?', $statement->getStatement());

        $grammar->limit(1);

        $container = $grammar->compile();
        $this->assertInstanceOf(StatementContainer::class, $container);
        $this->assertEquals(1, count($container));
        $statement = $container->pop();
        $this->assertInstanceOf(Statement::class, $statement);
        $this->assertEquals(['foo'], $statement->getArguments()->toArray());
        $this->assertEquals('DELETE FROM `edoger`.`users` WHERE `foo` = ? LIMIT 1', $statement->getStatement());
    }
}
