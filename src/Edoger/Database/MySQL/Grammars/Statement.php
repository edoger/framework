<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Grammars;

use Edoger\Database\MySQL\Arguments;

class Statement
{
    /**
     * The SQL statement.
     *
     * @var string
     */
    protected $statement;

    /**
     * The statement binding parameter manager.
     *
     * @var Arguments
     */
    protected $arguments;

    /**
     * The SQL statement constructor.
     *
     * @param string    $statement The SQL statement.
     * @param Arguments $arguments The statement binding parameter manager.
     *
     * @return void
     */
    public function __construct(string $statement, Arguments $arguments)
    {
        $this->statement = $statement;
        $this->arguments = $arguments;
    }

    /**
     * Create a SQL statement instance.
     *
     * @param string         $statement The SQL statement.
     * @param Arguments|null $arguments The statement binding parameter manager.
     *
     * @return self
     */
    public static function create(string $statement, Arguments $arguments = null): self
    {
        if (is_null($arguments)) {
            $arguments = Arguments::create();
        }

        return new static($statement, $arguments);
    }

    /**
     * Get the statement binding parameter manager.
     *
     * @return Arguments
     */
    public function getArguments(): Arguments
    {
        return $this->arguments;
    }

    /**
     * Get the SQL statement.
     *
     * @return string
     */
    public function getStatement(): string
    {
        return $this->statement;
    }
}
