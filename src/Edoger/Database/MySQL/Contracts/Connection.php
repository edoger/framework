<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Contracts;

use PDO;

interface Connection
{
    /**
     * Get the server definition instance.
     *
     * @return Server
     */
    public function getServer(): Server;

    /**
     * Get the current connection name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Determine if the current database connection is already connected.
     *
     * @return bool
     */
    public function isConnected(): bool;

    /**
     * Connect to the server and create a PDO instance.
     * If the connection has been completed, the created PDO instance is returned directly.
     *
     * @return PDO
     */
    public function connect(): PDO;

    /**
     * Close the current connection.
     *
     * @return bool
     */
    public function close(): bool;
}
