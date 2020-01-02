<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL\Grammars;

use Countable;
use RuntimeException;
use IteratorAggregate;
use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Arguments;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Database\MySQL\Grammars\Statement;
use Edoger\Database\MySQL\Exceptions\GrammarException;
use Edoger\Database\MySQL\Grammars\StatementContainer;

class StatementContainerTest extends TestCase
{
    protected function createStatement(string $statement, Arguments $arguments = null)
    {
        return new Statement($statement, $arguments ?? Arguments::create());
    }

    protected function createStatementContainer(array $statements = [])
    {
        return new StatementContainer($statements);
    }

    public function testStatementContainerConstructorFail()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('Invalid statement instance.');

        $this->createStatementContainer([false]); // exception
    }

    public function testStatementContainerInstanceOfArrayable()
    {
        $container = $this->createStatementContainer();

        $this->assertInstanceOf(Arrayable::class, $container);
    }

    public function testStatementContainerInstanceOfCountable()
    {
        $container = $this->createStatementContainer();

        $this->assertInstanceOf(Countable::class, $container);
    }

    public function testStatementContainerInstanceOfIteratorAggregate()
    {
        $container = $this->createStatementContainer();

        $this->assertInstanceOf(IteratorAggregate::class, $container);
    }

    public function testStatementContainerIsEmpty()
    {
        $container = $this->createStatementContainer();
        $this->assertTrue($container->isEmpty());

        $container = $this->createStatementContainer([$this->createStatement('foo')]);
        $this->assertFalse($container->isEmpty());
    }

    public function testStatementContainerPush()
    {
        $container = $this->createStatementContainer();

        $this->assertEquals($container, $container->push($this->createStatement('foo')));
        $this->assertFalse($container->isEmpty());
    }

    public function testStatementContainerPop()
    {
        $container = $this->createStatementContainer();

        $statementA = $this->createStatement('foo');
        $statementB = $this->createStatement('bar');

        $container->push($statementA);
        $container->push($statementB);

        $this->assertEquals($statementA, $container->pop());
        $this->assertEquals($statementB, $container->pop());
    }

    public function testStatementContainerPopFail()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Can not get statement instance from empty statement container.');

        $this->createStatementContainer()->pop(); // exception
    }

    public function testStatementContainerHasOption()
    {
        $container = $this->createStatementContainer();

        $this->assertFalse($container->hasOption('foo'));
        $container->setOption('foo', 1);
        $this->assertTrue($container->hasOption('foo'));
    }

    public function testStatementContainerGetOption()
    {
        $container = $this->createStatementContainer();

        $this->assertNull($container->getOption('foo'));
        $this->assertEquals('foo', $container->getOption('foo', 'foo'));
        $container->setOption('foo', 1);
        $this->assertEquals(1, $container->getOption('foo'));
    }

    public function testStatementContainerGetOptions()
    {
        $container = $this->createStatementContainer();

        $this->assertEquals([], $container->getOptions());
        $container->setOption('foo', 1);
        $this->assertEquals(['foo' => 1], $container->getOptions());
    }

    public function testStatementContainerSetOption()
    {
        $container = $this->createStatementContainer();

        $this->assertEquals($container, $container->setOption('foo', 1));
        $this->assertEquals(['foo' => 1], $container->getOptions());
        $this->assertEquals($container, $container->setOption('bar', 2));
        $this->assertEquals(['foo' => 1, 'bar' => 2], $container->getOptions());
        $this->assertEquals($container, $container->setOption('bar', 3));
        $this->assertEquals(['foo' => 1, 'bar' => 3], $container->getOptions());
    }

    public function testStatementContainerReplaceOptions()
    {
        $container = $this->createStatementContainer();

        $this->assertEquals($container, $container->replaceOptions([]));
        $this->assertEquals([], $container->getOptions());
        $this->assertEquals($container, $container->replaceOptions(['foo']));
        $this->assertEquals(['foo'], $container->getOptions());
        $this->assertEquals($container, $container->replaceOptions(['bar']));
        $this->assertEquals(['bar'], $container->getOptions());
    }

    public function testStatementContainerDeleteOption()
    {
        $container = $this->createStatementContainer();

        $this->assertEquals([], $container->getOptions());
        $this->assertEquals($container, $container->deleteOption('foo'));
        $this->assertEquals([], $container->getOptions());
        $container->replaceOptions(['foo' => 'foo', 'bar' => 'bar']);
        $this->assertEquals(['foo' => 'foo', 'bar' => 'bar'], $container->getOptions());
        $this->assertEquals($container, $container->deleteOption('foo'));
        $this->assertEquals(['bar' => 'bar'], $container->getOptions());
        $this->assertEquals($container, $container->deleteOption('bar'));
        $this->assertEquals([], $container->getOptions());
    }

    public function testStatementContainerClearOptions()
    {
        $container = $this->createStatementContainer();

        $this->assertEquals($container, $container->clearOptions());
        $this->assertEquals([], $container->getOptions());
        $container->replaceOptions(['foo' => 'foo', 'bar' => 'bar']);
        $this->assertEquals(['foo' => 'foo', 'bar' => 'bar'], $container->getOptions());
        $this->assertEquals($container, $container->clearOptions());
        $this->assertEquals([], $container->getOptions());
    }

    public function testStatementContainerArrayable()
    {
        $container = $this->createStatementContainer();

        $statementA = $this->createStatement('foo');
        $statementB = $this->createStatement('bar');

        $container->push($statementA);
        $container->push($statementB);

        $this->assertEquals([$statementA, $statementB], $container->toArray());
    }

    public function testStatementContainerCountable()
    {
        $container = $this->createStatementContainer();

        $this->assertEquals(0, count($container));
        $container->push($this->createStatement('foo'));
        $this->assertEquals(1, count($container));
    }

    public function testStatementContainerIteratorAggregate()
    {
        $statements = [$this->createStatement('foo'), $this->createStatement('bar')];
        $container  = $this->createStatementContainer($statements);

        foreach ($container as $key => $value) {
            $this->assertEquals($statements[$key], $value);
        }
    }
}
