<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Exceptions;

use Throwable;
use Edoger\Database\Exceptions\ExecutionException as DatabaseExecutionException;

class ExecutionException extends DatabaseExecutionException
{
    /**
     * The failed SQL statement.
     *
     * @var string
     */
    protected $statement;

    /**
     * The SQL statement binding parameters.
     *
     * @var array
     */
    protected $arguments;

    /**
     * The server name.
     *
     * @var string
     */
    protected $server;

    /**
     * The execution exception constructor.
     *
     * @param string         $statement The failed SQL statement.
     * @param array          $arguments The SQL statement binding parameters.
     * @param string         $server    The server name.
     * @param string         $message   The exception message.
     * @param int            $code      The exception code.
     * @param Throwable|null $previous  The previous exception used for the exception chaining.
     *
     * @return void
     */
    public function __construct(string $statement, array $arguments, string $server, string $message, int $code = 1, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->statement = $statement;
        $this->arguments = $arguments;
        $this->server    = $server;
    }

    /**
     * Get the failed SQL statement.
     *
     * @return string
     */
    public function getStatement(): string
    {
        return $this->statement;
    }

    /**
     * Get the SQL statement binding parameters.
     *
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Get the server name.
     *
     * @return string
     */
    public function getServerName(): string
    {
        return $this->server;
    }
}
