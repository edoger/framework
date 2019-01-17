<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL;

use Edoger\Database\MySQL\Contracts\Server as ServerContract;

class Server implements ServerContract
{
    /**
     * The current server name.
     *
     * @var string
     */
    protected $name;

    /**
     * The current server host name.
     *
     * @var string
     */
    protected $host;

    /**
     * The current server port number.
     *
     * @var int
     */
    protected $port;

    /**
     * The current server unix socket path.
     *
     * @var string
     */
    protected $socket;

    /**
     * The current server user name.
     *
     * @var string
     */
    protected $username;

    /**
     * The current server user password.
     *
     * @var string
     */
    protected $password;

    /**
     * The current server default database name.
     *
     * @var string
     */
    protected $dbname;

    /**
     * The current server default client charset.
     *
     * @var string
     */
    protected $charset;

    /**
     * The server constructor.
     *
     * @param string $name     The current server name.
     * @param string $host     The current server host name.
     * @param int    $port     The current server port number.
     * @param string $socket   The current server unix socket path.
     * @param string $username The current server user name.
     * @param string $password The current server user password.
     * @param string $dbname   The current server default database name.
     * @param string $charset  The current server default client charset.
     *
     * @return void
     */
    public function __construct(string $name, string $host = '127.0.0.1', int $port = 3306, string $socket = '', string $username = 'root', string $password = '', string $dbname = '', string $charset = 'utf8mb4')
    {
        $this->name     = $name;
        $this->host     = $host;
        $this->port     = $port;
        $this->socket   = $socket;
        $this->username = $username;
        $this->password = $password;
        $this->dbname   = $dbname;
        $this->charset  = $charset;
    }

    /**
     * Get the current server name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the current server host name.
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Get the current server port number.
     *
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * Get the current server unix socket path.
     *
     * @return string
     */
    public function getUnixSocketPath(): string
    {
        return $this->socket;
    }

    /**
     * Get the current server user name.
     *
     * @return string
     */
    public function getUserName(): string
    {
        return $this->username;
    }

    /**
     * Get the current server user password.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Get the current server default database name.
     *
     * @return string
     */
    public function getDatabaseName(): string
    {
        return $this->dbname;
    }

    /**
     * Get the current server default client charset.
     *
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * Generate the data source name (DSN) of the current server.
     *
     * @return string
     */
    public function generateDsn(): string
    {
        // If MySQL unix sockets are provided, unix sockets are preferred and TCP network
        // connections are automatically disabled.
        if ('' !== $socket = $this->getUnixSocketPath()) {
            $dsn = 'mysql:unix_socket='.$socket;
        } else {
            $dsn = 'mysql:host='.$this->getHost().';port='.$this->getPort();
        }

        // If a default database is provided, it is automatically appended to the data source name.
        if ('' !== $dbname = $this->getDatabaseName()) {
            $dsn .= ';dbname='.$dbname;
        }

        return $dsn.';charset='.$this->getCharset();
    }
}
