<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL;

use InvalidArgumentException;

class SocketServer extends Server
{
    /**
     * The unix socket server constructor.
     *
     * @param string $name     The current server name.
     * @param string $socket   The current server unix socket path.
     * @param string $username The current server user name.
     * @param string $password The current server user password.
     * @param string $dbname   The current server default database name.
     * @param string $charset  The current server default client charset.
     *
     * @throws InvalidArgumentException Thrown when the MySQL unix socket path is invalid.
     *
     * @return void
     */
    public function __construct(string $name, string $socket, string $username = 'root', string $password = '', string $dbname = '', string $charset = 'utf8mb4')
    {
        if ('' === $socket) {
            throw new InvalidArgumentException('Invalid MySQL unix socket path.');
        }

        parent::__construct($name, '', 0, $socket, $username, $password, $dbname, $charset);
    }
}
