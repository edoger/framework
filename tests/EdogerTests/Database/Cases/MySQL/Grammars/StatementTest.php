<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL\Grammars;

use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Arguments;
use Edoger\Database\MySQL\Grammars\Statement;

class StatementTest extends TestCase
{
    protected function createStatement(string $statement, Arguments $arguments = null)
    {
        return new Statement($statement, $arguments ?? Arguments::create());
    }

    public function testStatementCreate()
    {
        $this->assertInstanceOf(Statement::class, Statement::create('test'));
        $this->assertInstanceOf(Statement::class, Statement::create('test', Arguments::create('test')));
    }

    public function testStatementGetArguments()
    {
        $statement = $this->createStatement('test');

        $this->assertInstanceOf(Arguments::class, $statement->getArguments());
        $this->assertEquals([], $statement->getArguments()->toArray());

        $statement = $this->createStatement('test', Arguments::create('test'));

        $this->assertInstanceOf(Arguments::class, $statement->getArguments());
        $this->assertEquals(['test'], $statement->getArguments()->toArray());
    }

    public function testStatementGetStatement()
    {
        $statement = $this->createStatement('test');

        $this->assertEquals('test', $statement->getStatement());
    }
}
