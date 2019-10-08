<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL;

use PDO;
use Throwable;
use PDOStatement;
use Edoger\Util\Arr;
use InvalidArgumentException;
use Edoger\Database\MySQL\Contracts\Connection;
use Edoger\Database\MySQL\Exceptions\ExecutionException;

class Actuator
{
    /**
     * The database server connection.
     *
     * @var Connection
     */
    protected $connection;

    /**
     * The actuator constructor.
     *
     * @param Connection $connection The database server connection.
     *
     * @return void
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get the currently bound database connection.
     *
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * Executes a given SQL statement.
     *
     * @param string $statement The given SQL statement.
     *
     * @throws ExecutionException
     * @throws InvalidArgumentException Thrown when the SQL statement is invalid.
     *
     * @return void
     */
    public function execute(string $statement): void
    {
        if ('' === $statement) {
            throw new InvalidArgumentException('The SQL statement can not be an empty string.');
        }

        // Executed only, ignore the return value.
        // These statements may not have a meaningful return value.
        $this->doSimpleExecute($statement);
    }

    /**
     * Execute a query SQL statement.
     *
     * @param string $statement The given SQL statement.
     * @param array $arguments The SQL statement binding parameters.
     *
     * @throws ExecutionException
     * @throws InvalidArgumentException Thrown when the SQL statement is invalid.
     *
     * @return PDOStatement
     */
    public function query(string $statement, array $arguments = []): PDOStatement
    {
        if ('' === $statement) {
            throw new InvalidArgumentException('The SQL statement can not be an empty string.');
        }

        if (empty($arguments)) {
            return $this->doSimpleQuery($statement);
        } else {
            return $this->doPrepareAndExecute($statement, $arguments);
        }
    }

    /**
     * Execute an update SQL statement.
     *
     * @param string $statement The given SQL statement.
     * @param array $arguments The SQL statement binding parameters.
     *
     * @throws ExecutionException
     * @throws InvalidArgumentException Thrown when the SQL statement is invalid.
     *
     * @return int
     */
    public function update(string $statement, array $arguments = []): int
    {
        if ('' === $statement) {
            throw new InvalidArgumentException('The SQL statement can not be an empty string.');
        }

        if (empty($arguments)) {
            return $this->doSimpleExecute($statement);
        } else {
            return $this->doPrepareAndExecute($statement, $arguments)->rowCount();
        }
    }

    /**
     * Execute an insert SQL statement.
     *
     * @param string $statement The given SQL statement.
     * @param array $arguments The SQL statement binding parameters.
     *
     * @throws ExecutionException
     * @throws InvalidArgumentException Thrown when the SQL statement is invalid.
     *
     * @return string
     */
    public function insert(string $statement, array $arguments = []): string
    {
        if ('' === $statement) {
            throw new InvalidArgumentException('The SQL statement can not be an empty string.');
        }

        if (empty($arguments)) {
            $this->doSimpleExecute($statement);
        } else {
            $this->doPrepareAndExecute($statement, $arguments);
        }

        return $this->getConnection()->connect()->lastInsertId();
    }

    /**
     * Execute a delete SQL statement.
     *
     * @param string $statement The given SQL statement.
     * @param array $arguments The SQL statement binding parameters.
     *
     * @throws ExecutionException
     * @throws InvalidArgumentException Thrown when the SQL statement is invalid.
     *
     * @return int
     */
    public function delete(string $statement, array $arguments = []): int
    {
        if ('' === $statement) {
            throw new InvalidArgumentException('The SQL statement can not be an empty string.');
        }

        if (empty($arguments)) {
            return $this->doSimpleExecute($statement);
        } else {
            return $this->doPrepareAndExecute($statement, $arguments)->rowCount();
        }
    }

    /**
     * Throws an "Edoger\Database\MySQL\Exceptions\ExecutionException" instance.
     *
     * @param string $message The exception message.
     * @param string $statement The failed SQL statement.
     * @param array $arguments The SQL statement binding parameters.
     * @param Throwable|null $previous The previous exception used for the exception chaining.
     *
     * @throws ExecutionException Always throw.
     *
     * @return void
     */
    protected function throwExecutionException(string $message, string $statement, array $arguments = [], Throwable $previous = null): void
    {
        $server = $this->getConnection()->getName();
        $code   = $previous ? (int) $previous->getCode() : 1;

        throw new ExecutionException($statement, $arguments, $server, $message, $code, $previous);
    }

    /**
     * Get the error message for the given PDO instance.
     *
     * @param PDO $pdo The given PDO instance.
     *
     * @return string
     */
    protected function getPdoErrorMessage(PDO $pdo): string
    {
        $error = $pdo->errorInfo();

        return Arr::get(Arr::wrap($error), 2) ?: 'Unknown error';
    }

    /**
     * Get the error message for the given PDOStatement instance.
     *
     * @param PDOStatement $stmt The given PDOStatement instance.
     *
     * @return string
     */
    protected function getPdoStatementErrorMessage(PDOStatement $stmt): string
    {
        $error = $stmt->errorInfo();

        return Arr::get(Arr::wrap($error), 2) ?: 'Unknown error';
    }

    /**
     * Executes a given simple non-query SQL statement.
     *
     * @param string $statement The given SQL statement.
     *
     * @throws ExecutionException
     *
     * @return int
     */
    protected function doSimpleExecute(string $statement): int
    {
        $pdo  = $this->getConnection()->connect();
        $rows = $pdo->exec($statement);

        if (false === $rows) {
            $this->throwExecutionException($this->getPdoErrorMessage($pdo), $statement);
        }

        return $rows;
    }

    /**
     * Executes a given simple query SQL statement.
     *
     * @param string $statement The given SQL statement.
     *
     * @throws ExecutionException
     *
     * @return PDOStatement
     */
    protected function doSimpleQuery(string $statement): PDOStatement
    {
        $pdo  = $this->getConnection()->connect();
        $stmt = $pdo->query($statement);

        if (!$stmt) {
            $this->throwExecutionException($this->getPdoErrorMessage($pdo), $statement);
        }

        return $stmt;
    }

    /**
     * Executes a given SQL statement with the binding parameters.
     *
     * @param string $statement The given SQL statement.
     * @param array $arguments The SQL statement binding parameters.
     *
     * @throws ExecutionException
     *
     * @return PDOStatement
     */
    protected function doPrepareAndExecute(string $statement, array $arguments): PDOStatement
    {
        $pdo  = $this->getConnection()->connect();
        $stmt = $pdo->prepare($statement);

        if (!$stmt) {
            $this->throwExecutionException($this->getPdoErrorMessage($pdo), $statement, $arguments);
        }

        try {
            if (!$stmt->execute($arguments)) {
                $this->throwExecutionException(
                    $this->getPdoStatementErrorMessage($stmt),
                    $statement,
                    $arguments
                );
            }
        } catch (ExecutionException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->throwExecutionException($e->getMessage(), $statement, $arguments, $e);
        }

        return $stmt;
    }
}
