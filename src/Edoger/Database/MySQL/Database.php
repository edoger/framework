<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL;

use PDO;
use Edoger\Util\Arr;
use Edoger\Util\Validator;
use InvalidArgumentException;
use Edoger\Database\MySQL\Foundation\Util;

class Database
{
    /**
     * The database name.
     *
     * @var string
     */
    protected $name;

    /**
     * The SQL statement actuator.
     *
     * @var Edoger\Database\MySQL\Actuator
     */
    protected $actuator;

    /**
     * The database constructor.
     *
     * @param Actuator $actuator The SQL statement actuator.
     * @param string   $name     The database name.
     *
     * @throws InvalidArgumentException Thrown when the database name can not be determined.
     *
     * @return void
     */
    public function __construct(Actuator $actuator, string $name = '')
    {
        $this->name     = $name;
        $this->actuator = $actuator;

        if (!Validator::isNotEmptyString($name)) {
            // Get the actuator bound connection, maybe we will use it multiple times.
            $connection = $actuator->getConnection();

            // If the default database name is empty, automatically try to get the database name
            // from the server configuration.
            $name = $connection->getServer()->getDatabaseName();

            if (!Validator::isNotEmptyString($name) && $connection->isConnected()) {
                // If we can not get the default database name from the server configuration and
                // the database is already connected, we will automatically try to query
                // the default database name used by the current connection.
                $name = $this->getDatabaseNameFromConnection();
            }

            if (Validator::isNotEmptyString($name)) {
                $this->name = $name;
            } else {
                // We really can not determine the database name.
                throw new InvalidArgumentException(
                    'Unable to determine the database name.'
                );
            }
        }
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
     * Get the current SQL statement actuator.
     *
     * @return Edoger\Database\MySQL\Actuator
     */
    public function getActuator(): Actuator
    {
        return $this->actuator;
    }

    /**
     * Get the database name from the current connection.
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
     * @return array
     */
    public function getDatabaseTables(): array
    {
        return $this
            ->getActuator()
            ->query('SHOW TABLES FROM '.Util::wrap($this->getDatabaseName()))
            ->fetchAll(PDO::FETCH_FUNC, function ($table) {
                return $table;
            });
    }
}
