<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\Contracts;

interface Server
{
    /**
     * Get the current server name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the current server user name.
     *
     * @return string
     */
    public function getUserName(): string;

    /**
     * Get the current server user password.
     *
     * @return string
     */
    public function getPassword(): string;

    /**
     * Get the current server host name.
     *
     * @return string
     */
    public function getHost(): string;

    /**
     * Get the current server port number.
     *
     * @return int
     */
    public function getPort(): int;

    /**
     * Get the current server unix socket path.
     *
     * @return string
     */
    public function getUnixSocketPath(): string;

    /**
     * Get the current server default database name.
     *
     * @return string
     */
    public function getDatabaseName(): string;

    /**
     * Get the current server default client charset.
     *
     * @return string
     */
    public function getCharset(): string;

    /**
     * Generate the data source name (DSN) of the current server.
     *
     * @return string
     */
    public function generateDsn(): string;
}
