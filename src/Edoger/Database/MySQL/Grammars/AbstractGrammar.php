<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Grammars;

use Edoger\Database\MySQL\Table;
use Edoger\Database\MySQL\Database;
use Edoger\Database\MySQL\Arguments;

abstract class AbstractGrammar
{
    /**
     * The MySQL database instance.
     *
     * @var Edoger\Database\MySQL\Database
     */
    protected $database;

    /**
     * The MySQL database table instance.
     *
     * @var Edoger\Database\MySQL\Table
     */
    protected $table;

    /**
     * The abstract grammar constructor.
     *
     * @param Edoger\Database\MySQL\Database $database The MySQL database instance.
     * @param Edoger\Database\MySQL\Table    $table    The MySQL database table instance.
     *
     * @return void
     */
    public function __construct(Database $database, Table $table)
    {
        $this->database = $database;
        $this->table    = $table;
    }

    /**
     * Create a grammar instance.
     *
     * @param Edoger\Database\MySQL\Database $database The MySQL database instance.
     * @param Edoger\Database\MySQL\Table    $table    The MySQL database table instance.
     *
     * @return self
     */
    public static function create(Database $database, Table $table): self
    {
        return new static($database, $table);
    }

    /**
     * Create a grammar instance from the given grammar instance.
     *
     * @param self $grammar The given grammar instance.
     *
     * @return self
     */
    public static function createFromGrammar(self $grammar): self
    {
        return static::create($grammar->getDatabase(), $grammar->getTable());
    }

    /**
     * Get MySQL database instance.
     *
     * @return Edoger\Database\MySQL\Database
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * Get MySQL database table instance.
     *
     * @return Edoger\Database\MySQL\Table
     */
    public function getTable(): Table
    {
        return $this->table;
    }

    /**
     * Get wrapped full table name.
     *
     * @return string
     */
    public function getWrappedFullTableName(): string
    {
        return $this->getDatabase()->getWrappedDatabaseName().'.'.$this->getTable()->getWrappedName();
    }

    /**
     * Compile the current instance to a statement string.
     *
     * @param Edoger\Database\MySQL\Arguments|null $arguments The statement binding parameter manager.
     *
     * @return Edoger\Database\MySQL\Grammars\SQLStatement
     */
    abstract public function compile(Arguments $arguments = null): SQLStatement;
}
