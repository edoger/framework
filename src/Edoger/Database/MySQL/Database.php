<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL;

use PDO;
use Edoger\Util\Arr;
use Edoger\Util\Validator;
use InvalidArgumentException;
use Edoger\Database\MySQL\Foundation\Util;
use Edoger\Database\Exceptions\ExecutionException;

class Database
{
    /**
     * The SQL statement actuator.
     *
     * @var Actuator
     */
    protected $actuator;

    /**
     * The transaction manager.
     *
     * @var Transaction
     */
    protected $transaction;

    /**
     * The database name.
     *
     * @var string
     */
    protected $name;

    /**
     * The current database table names.
     *
     * @var null|array
     */
    protected $tables = null;

    /**
     * The database constructor.
     *
     * @param Actuator $actuator The SQL statement actuator.
     * @param string $name The database name.
     *
     * @throws ExecutionException
     * @throws InvalidArgumentException Thrown when the database name can not be determined.
     *
     * @return void
     */
    public function __construct(Actuator $actuator, string $name = '')
    {
        $this->actuator    = $actuator;
        $this->transaction = new Transaction($actuator->getConnection());
        $this->name        = $this->formatDatabaseName($name);
    }

    /**
     * Format the database name.
     *
     * @param string $name The database name.
     *
     * @throws ExecutionException
     *
     * @return string
     */
    protected function formatDatabaseName(string $name): string
    {
        if (Validator::isNotEmptyString($name)) {
            return $name;
        }

        // Get the actuator bound connection, maybe we will use it multiple times.
        $connection = $this->getActuator()->getConnection();

        // If the default database name is empty, automatically try to get the database name
        // from the server configuration.
        $name = $connection->getServer()->getDatabaseName();

        if (Validator::isNotEmptyString($name)) {
            return $name;
        }

        // If we can not get the default database name from the server configuration and
        // the database is already connected, we will automatically try to query
        // the default database name used by the current connection.
        if ($connection->isConnected()) {
            $name = $this->getDatabaseNameFromConnection();

            if (Validator::isNotEmptyString($name)) {
                return $name;
            }
        }

        // We really can not determine the database name.
        throw new InvalidArgumentException('Unable to determine the database name.');
    }

    /**
     * Get the current default database name.
     *
     * @return string
     */
    public function getDatabaseName(): string
    {
        return $this->name;
    }

    /**
     * Get the current wrapped default database name.
     *
     * @return string
     */
    public function getWrappedDatabaseName(): string
    {
        return Util::wrap($this->getDatabaseName());
    }

    /**
     * Get the current SQL statement actuator.
     *
     * @return Actuator
     */
    public function getActuator(): Actuator
    {
        return $this->actuator;
    }

    /**
     * Get the database name from the current connection.
     *
     * @throws ExecutionException
     *
     * @return string
     */
    public function getDatabaseNameFromConnection(): string
    {
        $row = $this->getActuator()->query('SELECT DATABASE()')->fetch(PDO::FETCH_NUM);

        return (string) Arr::first($row);
    }

    /**
     * Use the given database name as the default database name for the current connection.
     *
     * @param string $name The given database name.
     *
     * @throws ExecutionException
     *
     * @return self
     */
    public function useDatabaseName(string $name = ''): self
    {
        // If no given database name, then automatically use the current database name.
        if ('' === $name) {
            $name = $this->getDatabaseName();
        }

        $this->getActuator()->execute('USE '.Util::wrap($name));

        return $this;
    }

    /**
     * Get the current database table names.
     *
     * @param bool $noCache Do not read cached data.
     *
     * @throws ExecutionException
     *
     * @return array
     */
    public function getDatabaseTables(bool $noCache = false): array
    {
        if (is_null($this->tables) || $noCache) {
            $this->tables = $this
                ->getActuator()
                ->query('SHOW TABLES FROM '.$this->getWrappedDatabaseName())
                ->fetchAll(PDO::FETCH_FUNC, function ($table) {
                    return $table;
                });
        }

        return $this->tables;
    }

    /**
     * Get the current transaction manager.
     *
     * @return Transaction
     */
    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }
}
