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
                $name = Arr::first($actuator->query('SELECT DATABASE()')->fetch(PDO::FETCH_NUM));
            }

            if (!Validator::isNotEmptyString($name)) {
                // We really can not determine the database name.
                throw new InvalidArgumentException(
                    'Unable to determine the database name.'
                );
            }
        }

        $this->name     = $name;
        $this->actuator = $actuator;
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
}
