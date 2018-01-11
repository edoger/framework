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
use Throwable;
use Edoger\Database\MySQL\Contracts\Server;
use Edoger\Database\Exceptions\ConnectionException;
use Edoger\Database\MySQL\Contracts\Connection as ConnectionContract;

class Connection implements ConnectionContract
{
    /**
     * The server definition instance.
     *
     * @var Edoger\Database\MySQL\Contracts\Server
     */
    protected $server;

    /**
     * The PDO instance.
     *
     * @var PDO
     */
    protected $pdo;

    /**
     * The connection constructor.
     *
     * @param Edoger\Database\MySQL\Contracts\Server $server The server definition instance.
     *
     * @return void
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Get the current connection name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->server->getName();
    }

    /**
     * Connect to the server and create a PDO instance.
     * If the connection has been completed, the created PDO instance is returned directly.
     *
     * @throws Edoger\Database\Exceptions\ConnectionException Thrown when the connection fails.
     *
     * @return PDO
     */
    public function connect(): PDO
    {
        // Make sure to create only one PDO instance before closing the current connection.
        if ($this->pdo) {
            return $this->pdo;
        }

        $dsn      = $this->server->generateDsn();
        $username = $this->server->getUserName();
        $password = $this->server->getPassword();

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
}
