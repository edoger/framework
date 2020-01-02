<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
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
     * @var Database
     */
    protected $database;

    /**
     * The MySQL database table instance.
     *
     * @var Table
     */
    protected $table;

    /**
     * The abstract grammar constructor.
     *
     * @param Database $database The MySQL database instance.
     * @param Table    $table    The MySQL database table instance.
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
     * @param Database $database The MySQL database instance.
     * @param Table $table    The MySQL database table instance.
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
     * @return Database
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * Get MySQL database table instance.
     *
     * @return Table
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
     * Compile the current SQL statement.
     *
     * @return StatementContainer
     */
    abstract public function compile(): StatementContainer;
}
