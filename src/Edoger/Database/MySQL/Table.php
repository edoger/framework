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
use Countable;
use ArrayIterator;
use Edoger\Util\Arr;
use RuntimeException;
use IteratorAggregate;
use Edoger\Util\Validator;
use InvalidArgumentException;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Database\MySQL\Foundation\Util;

class Table implements Arrayable, Countable, IteratorAggregate
{
    /**
     * The database table name.
     *
     * @var string
     */
    protected $name;

    /**
     * The database table primary key name.
     *
     * @var string
     */
    protected $primaryKey;

    /**
     * The database table fields.
     *
     * @var array
     */
    protected $fields;

    /**
     * The MySQL table constructor.
     *
     * @param string   $name       The database table name.
     * @param string   $primaryKey The database table primary key name.
     * @param iterable $fields
     *
     * @throws InvalidArgumentException Thrown when the database table name is empty.
     *
     * @return void
     */
    public function __construct(string $name, string $primaryKey = 'id', iterable $fields = [])
    {
        if (!Validator::isNotEmptyString($name)) {
            throw new InvalidArgumentException('The database table name can not be empty.');
        }

        $this->name = $name;

        $this->setPrimaryKey($primaryKey);
        $this->setFields($fields);
    }

    /**
     * Get the current database table name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the current database wrapped table name.
     *
     * @return string
     */
    public function getWrappedName(): string
    {
        return Util::wrap($this->getName());
    }

    /**
     * Get the primary key name of the current database table.
     *
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * Get the wrapped primary key name of the current database table.
     *
     * @return string
     */
    public function getWrappedPrimaryKey(): string
    {
        return Util::wrap($this->getPrimaryKey());
    }

    /**
     * Set the primary key name of the current database table.
     *
     * @param string $primaryKey The database table primary key name.
     *
     * @throws InvalidArgumentException Thrown when the database table primary key name is empty.
     *
     * @return self
     */
    public function setPrimaryKey(string $primaryKey): self
    {
        // We require the database table must have a self-increasing primary key.
        // It is usually straightforward to add an auto-increment primary key to a database table,
        // so we do not intend to support database tables that do not increment the primary key.
        if (!Validator::isNotEmptyString($primaryKey)) {
            throw new InvalidArgumentException(
                'The database table primary key name can not be empty.'
            );
        }

        $this->primaryKey = $primaryKey;

        return $this;
    }

    /**
     * Determine whether the current database table fields is empty.
     *
     * @return bool
     */
    public function isEmptyFields(): bool
    {
        return empty($this->fields);
    }

    /**
     * Get the current database table fields.
     *
     * @return array
     */
    public function getFields(): array
    {
        if ($this->isEmptyFields()) {
            return [];
        }

        return Arr::keys($this->fields);
    }

    /**
     * Get the current database wrapped table fields.
     *
     * @return array
     */
    public function getWrappedFields(): array
    {
        if ($this->isEmptyFields()) {
            return [];
        }

        return Arr::values($this->fields);
    }

    /**
     * Set the current database table fields.
     *
     * @param iterable $fields The database table fields.
     *
     * @throws InvalidArgumentException Thrown when the database field name is invalid.
     *
     * @return self
     */
    public function setFields(iterable $fields): self
    {
        $wrapped = [];

        foreach ($fields as $field) {
            if (!Validator::isNotEmptyString($field)) {
                throw new InvalidArgumentException('Invalid database table field.');
            }

            $wrapped[$field] = Util::wrap($field);
        }

        // Automatically add primary key to the field list.
        // All database queries, primary key fields are required.
        if (!empty($wrapped)) {
            $wrapped[$this->getPrimaryKey()] = $this->getWrappedPrimaryKey();
        }

        $this->fields = $wrapped;

        return $this;
    }

    /**
     * Initialize the current database table fields and primary key from the given database instance.
     *
     * @param Edoger\Database\MySQL\Database $database The database instance.
     *
     * @throws RuntimeException Thrown when the current database table does not exist.
     * @throws RuntimeException Thrown when the current database table primary key does not exist.
     *
     * @return self
     */
    public function fromDatabase(Database $database): self
    {
        // Check if the database table really exists.
        if (!in_array($this->getName(), $database->getDatabaseTables())) {
            throw new RuntimeException(
                sprintf(
                    'The table "%s" does not exist in database "%s".',
                    $this->getName(),
                    $database->getDatabaseName()
                )
            );
        }

        $fields     = [];
        $primaryKey = null;
        $statement  = sprintf('DESCRIBE %s.%s', $database->getWrappedDatabaseName(), $this->getWrappedName());

        // Query the table structure of the current database table.
        // The row structure: ["Field", "Type", "Null", "Key", "Default", "Extra"]
        foreach ($database->getActuator()->query($statement)->fetchAll(PDO::FETCH_ASSOC) as $row) {
            // Determine the autoincrement primary key column.
            if ('PRI' === Arr::get($row, 'Key') && 'auto_increment' === Arr::get($row, 'Extra')) {
                $primaryKey = Arr::get($row, 'Field');
            } else {
                $fields[] = Arr::get($row, 'Field');
            }
        }

        // We require that each data table must have an autoincrement primary key column.
        if (is_null($primaryKey)) {
            throw new RuntimeException(
                sprintf(
                    'The table "%s" primary key column does not exist in database "%s".',
                    $this->getName(),
                    $database->getDatabaseName()
                )
            );
        }

        return $this->setPrimaryKey($primaryKey)->setFields($fields);
    }

    /**
     * Returns the current table instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->fields;
    }

    /**
     * Gets the size of the current table fields.
     *
     * @return int
     */
    public function count()
    {
        return count($this->fields);
    }

    /**
     * Gets an iterator instance of the current table fields.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->fields);
    }
}
