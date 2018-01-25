<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL;

use Edoger\Database\MySQL\Contracts\Connection;

class Transaction
{
    /**
     * The database server connection.
     *
     * @var Edoger\Database\MySQL\Contracts\Connection
     */
    protected $connection;

    /**
     * The transaction constructor.
     *
     * @param Edoger\Database\MySQL\Contracts\Connection $connection The database server connection.
     *
     * @return void
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get the current transaction status.
     *
     * @return bool
     */
    public function status(): bool
    {
        // Why check the database connection has been created?
        // Because before the completion of the database connection is created,
        // there will be no active transaction.
        if ($this->connection->isConnected()) {
            return $this->connection->connect()->inTransaction();
        }

        return false;
    }

    /**
     * Open a database transaction.
     *
     * @return bool
     */
    public function open(): bool
    {
        if ($this->status()) {
            return true;
        }

        return $this->connection->connect()->beginTransaction();
    }

    /**
     * Submit the current transaction.
     *
     * @return bool
     */
    public function commit(): bool
    {
        if ($this->status()) {
            return $this->connection->connect()->commit();
        }

        return false;
    }

    /**
     * Roll back the current transaction.
     *
     * @return bool
     */
    public function back(): bool
    {
        if ($this->status()) {
            return $this->connection->connect()->rollBack();
        }

        return false;
    }
}
