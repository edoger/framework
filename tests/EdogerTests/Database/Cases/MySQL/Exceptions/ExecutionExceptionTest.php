<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL\Exceptions;

use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Exceptions\ExecutionException;
use Edoger\Database\Exceptions\ExecutionException as DatabaseExecutionException;

class ExecutionExceptionTest extends TestCase
{
    public function testExecutionExceptionInstanceOfDatabaseExecutionException()
    {
        $exception = new ExecutionException('TEST STATEMENT', ['TEST'], 'test', 'test');

        $this->assertInstanceOf(DatabaseExecutionException::class, $exception);
    }

    public function testExecutionExceptionGetStatement()
    {
        $exception = new ExecutionException('TEST STATEMENT', ['TEST'], 'test', 'test');

        $this->assertEquals('TEST STATEMENT', $exception->getStatement());
    }

    public function testExecutionExceptionGetArguments()
    {
        $exception = new ExecutionException('TEST STATEMENT', ['TEST'], 'test', 'test');

        $this->assertEquals(['TEST'], $exception->getArguments());
    }

    public function testExecutionExceptionGetServerName()
    {
        $exception = new ExecutionException('TEST STATEMENT', ['TEST'], 'test', 'test');

        $this->assertEquals('test', $exception->getServerName());
    }
}
