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
use Edoger\Database\MySQL\Arguments;
use Edoger\Database\MySQL\Grammars\SQLStatement;

class SQLStatementTest extends TestCase
{
    protected function createSQLStatement(Arguments $arguments = null)
    {
        return new SQLStatement($arguments);
    }

    public function testSQLStatementGetArguments()
    {
        $arguments = Arguments::create(['foo']);
        $statement = $this->createSQLStatement($arguments);

        $this->assertInstanceOf(Arguments::class, $statement->getArguments());
        $this->assertEquals($arguments, $statement->getArguments());
        $this->assertInstanceOf(Arguments::class, $this->createSQLStatement()->getArguments());
    }

    public function testSQLStatementGetStatement()
    {
        $statement = $this->createSQLStatement();

        $this->assertEquals('', $statement->getStatement());
        $statement->setStatement('foo');
        $this->assertEquals('foo', $statement->getStatement());
    }

    public function testSQLStatementSetStatement()
    {
        $statement = $this->createSQLStatement();

        $this->assertEquals($statement, $statement->setStatement('foo'));
        $this->assertEquals('foo', $statement->getStatement());
    }

    public function testSQLStatementHasOption()
    {
        $statement = $this->createSQLStatement();

        $this->assertFalse($statement->hasOption('foo'));
        $statement->setOption('foo', 1);
        $this->assertTrue($statement->hasOption('foo'));
    }

    public function testSQLStatementGetOption()
    {
        $statement = $this->createSQLStatement();

        $this->assertNull($statement->getOption('foo'));
        $this->assertEquals('foo', $statement->getOption('foo', 'foo'));
        $statement->setOption('foo', 1);
        $this->assertEquals(1, $statement->getOption('foo'));
    }

    public function testSQLStatementGetOptions()
    {
        $statement = $this->createSQLStatement();

        $this->assertEquals([], $statement->getOptions());
        $statement->setOption('foo', 1);
        $this->assertEquals(['foo' => 1], $statement->getOptions());
    }

    public function testSQLStatementSetOption()
    {
        $statement = $this->createSQLStatement();

        $this->assertEquals($statement, $statement->setOption('foo', 1));
        $this->assertEquals(['foo' => 1], $statement->getOptions());
        $this->assertEquals($statement, $statement->setOption('bar', 2));
        $this->assertEquals(['foo' => 1, 'bar' => 2], $statement->getOptions());
        $this->assertEquals($statement, $statement->setOption('bar', 3));
        $this->assertEquals(['foo' => 1, 'bar' => 3], $statement->getOptions());
    }

    public function testSQLStatementReplaceOptions()
    {
        $statement = $this->createSQLStatement();

        $this->assertEquals($statement, $statement->replaceOptions([]));
        $this->assertEquals([], $statement->getOptions());
        $this->assertEquals($statement, $statement->replaceOptions(['foo']));
        $this->assertEquals(['foo'], $statement->getOptions());
        $this->assertEquals($statement, $statement->replaceOptions(['bar']));
        $this->assertEquals(['bar'], $statement->getOptions());
    }

    public function testSQLStatementDeleteOption()
    {
        $statement = $this->createSQLStatement();

        $this->assertEquals([], $statement->getOptions());
        $this->assertEquals($statement, $statement->deleteOption('foo'));
        $this->assertEquals([], $statement->getOptions());
        $statement->replaceOptions(['foo' => 'foo', 'bar' => 'bar']);
        $this->assertEquals(['foo' => 'foo', 'bar' => 'bar'], $statement->getOptions());
        $this->assertEquals($statement, $statement->deleteOption('foo'));
        $this->assertEquals(['bar' => 'bar'], $statement->getOptions());
        $this->assertEquals($statement, $statement->deleteOption('bar'));
        $this->assertEquals([], $statement->getOptions());
    }

    public function testSQLStatementClearOptions()
    {
        $statement = $this->createSQLStatement();

        $this->assertEquals($statement, $statement->clearOptions());
        $this->assertEquals([], $statement->getOptions());
        $statement->replaceOptions(['foo' => 'foo', 'bar' => 'bar']);
        $this->assertEquals(['foo' => 'foo', 'bar' => 'bar'], $statement->getOptions());
        $this->assertEquals($statement, $statement->clearOptions());
        $this->assertEquals([], $statement->getOptions());
    }
}
