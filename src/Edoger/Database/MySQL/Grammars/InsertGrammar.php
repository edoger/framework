<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Grammars;

use Edoger\Util\Arr;
use Edoger\Database\MySQL\Arguments;
use Edoger\Database\MySQL\Foundation\Util;
use Edoger\Database\MySQL\Exceptions\GrammarException;

class InsertGrammar extends AbstractGrammar
{
    /**
     * The column data cache.
     *
     * @var array
     */
    protected $columnData = [];

    /**
     * Determine whether there is a column data to be saved.
     *
     * @return bool
     */
    public function hasColumnData(): bool
    {
        return !empty($this->columnData);
    }

    /**
     * Get all the column data.
     *
     * @return array
     */
    public function getColumnData(): array
    {
        return $this->columnData;
    }

    /**
     * Set the column value for a given column.
     *
     * @param string $column The given column.
     * @param mixed  $value  The given column value.
     *
     * @return self
     */
    public function setColumn(string $column, $value): self
    {
        $value = is_null($value) ? [null] : Arr::convert($value);

        if (Arr::has($this->columnData, $column)) {
            foreach ($value as $v) {
                $this->columnData[$column][] = $v;
            }
        } else {
            $this->columnData[$column] = Arr::values($value);
        }

        return $this;
    }

    /**
     * Set the column value of a multi column.
     *
     * @param mixed $columns The given multi column values.
     *
     * @return self
     */
    public function setColumns($columns): self
    {
        foreach (Arr::convert($columns) as $column => $value) {
            $this->setColumn((string) $column, $value);
        }

        return $this;
    }

    /**
     * Compile the current SQL statement.
     *
     * @throws GrammarException Thrown when the column does not exist or is not writable.
     * @throws GrammarException Thrown when the column data is empty.
     *
     * @return StatementContainer
     */
    public function compile(): StatementContainer
    {
        if (!$this->hasColumnData()) {
            throw new GrammarException('The insert column data can not be empty.');
        }

        $fragments = Fragments::create(['INSERT INTO', $this->getWrappedFullTableName()]);
        $columns   = $this->getColumnData();

        if ($this->getTable()->isEmptyFields()) {
            $fragments->push(Util::enclose(Util::columnize(Arr::keys($columns))));
        } else {
            // If the database table field list has been set, we will automatically check all the
            // column names.
            $allowed = $this->getTable()->toArray();
            $fields  = [];

            foreach (Arr::keys($columns) as $field) {
                if (!isset($allowed[$field])) {
                    throw new GrammarException(
                        sprintf('The data column "%s" does not exist or is not allowed to be written.', $field)
                    );
                }

                // Cache already wrapped field.
                $fields[] = $allowed[$field];
            }

            $fragments->push(Util::enclose(Util::columnize($fields, false)));
        }

        $matrix      = Util::matrixize($columns);
        $placeholder = Util::enclose(Util::columnize(array_fill(0, count($matrix), '?'), false));
        $statement   = $fragments->push('VALUES')->push($placeholder)->assemble();
        $container   = new StatementContainer();

        foreach (Util::transpose($matrix) as $row) {
            $container->push(Statement::create($statement, Arguments::create($row)));
        }

        return $container;
    }
}
