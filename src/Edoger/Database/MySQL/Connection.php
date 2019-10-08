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
use Edoger\Database\MySQL\Contracts\Server;
use Edoger\Database\Exceptions\ConnectionException;
use Edoger\Database\MySQL\Contracts\Connection as ConnectionContract;

class Connection implements ConnectionContract
{
    /**
     * The server definition instance.
     *
     * @var Server
     */
    protected $server;

    /**
     * The PDO instance.
     *
     * @var PDO|null
     */
    protected $pdo = null;

    /**
     * The connection constructor.
     *
     * @param Server $server The server definition instance.
     *
     * @return void
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Get the server definition instance.
     *
     * @return Server
     */
    public function getServer(): Server
    {
        return $this->server;
    }

    /**
     * Get the current connection name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->getServer()->getName();
    }

    /**
     * Determine if the current database connection is already connected.
     *
     * @return bool
     */
    public function isConnected(): bool
    {
        return !is_null($this->pdo);
    }

    /**
     * Connect to the server and create a PDO instance.
     * If the connection has been completed, the created PDO instance is returned directly.
     *
     * @throws ConnectionException Thrown when the connection fails.
     *
     * @return PDO
     */
    public function connect(): PDO
    {
        // Make sure to create only one PDO instance before closing the current connection.
        if ($this->isConnected()) {
            return $this->pdo;
        }

        $dsn      = $this->getServer()->generateDsn();
        $username = $this->getServer()->getUserName();
        $password = $this->getServer()->getPassword();

        try {
            $pdo = new PDO($dsn, $username, $password);
        } catch (Throwable $e) {
            // Any exception will be rewritten as a "ConnectionException".
            throw new ConnectionException(
                sprintf('Failed to connect to server "%s": %s.', $this->getName(), $e->getMessage()),
                (int) $e->getCode(),
                $e
            );
        }

        $this->pdo = $pdo;

        return $pdo;
    }

    /**
     * Close the current connection.
     *
     * @return bool
     */
    public function close(): bool
    {
        $this->pdo = null;

        return true;
    }

    /**
     * Disconnect the current database server and reestablish the connection.
     *
     * @throws ConnectionException
     *
     * @return PDO
     */
    public function reconnect(): PDO
    {
        $this->close();

        return $this->connect();
    }
}
