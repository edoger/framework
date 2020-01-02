<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL;

class TcpServer extends Server
{
    /**
     * The tcp server constructor.
     *
     * @param string $name     The current server name.
     * @param string $host     The current server host name.
     * @param int    $port     The current server port number.
     * @param string $username The current server user name.
     * @param string $password The current server user password.
     * @param string $dbname   The current server default database name.
     * @param string $charset  The current server default client charset.
     *
     * @return void
     */
    public function __construct(string $name, string $host = '127.0.0.1', int $port = 3306, string $username = 'root', string $password = '', string $dbname = '', string $charset = 'utf8mb4')
    {
        parent::__construct($name, $host, $port, '', $username, $password, $dbname, $charset);
    }
}
