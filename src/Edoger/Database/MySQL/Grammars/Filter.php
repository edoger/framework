<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Grammars;

use Closure;
use Countable;
use Edoger\Util\Arr;
use Edoger\Container\Wrapper;
use Edoger\Database\MySQL\Arguments;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Database\MySQL\Foundation\Util;
use Edoger\Database\MySQL\Foundation\Operator;
use Edoger\Database\MySQL\Foundation\Connector;
use Edoger\Database\MySQL\Exceptions\GrammarException;

class Filter implements Arrayable, Countable
{
    /**
     * The default filter connector.
     *
     * @var string
     */
    protected $connector;

    /**
     * All added filters.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * The filter constructor.
     *
     * @param string $connector The default filter connector.
     *
     * @throws GrammarException
     *
     * @return void
     */
    public function __construct(string $connector = 'and')
    {
        $this->connector = Connector::standardize($connector);
    }

    /**
     * Standardize the given filter connector.
     *
     * @param string|null $connector The given filter connector.
     *
     * @throws GrammarException
     *
     * @return string
     */
    protected function standardizeConnector($connector): string
    {
        if (is_null($connector)) {
            return $this->connector;
        }

        return Connector::standardize($connector);
    }

    /**
     * Add a scalar column filter.
     *
     * @param string      $column The filter column name.
     * @param mixed       $value The filter column value.
     * @param string|bool $operator The filter operator.
     * @param string|null $connector The filter connector.
     *
     * @throws GrammarException
     *
     * @return self
     */
    protected function addScalarFilter(string $column, $value, $operator, $connector): self
    {
        $this->filters[] = [
            'compiler'  => 'simple',
            'column'    => $column,
            'value'     => $value,
            'operator'  => Operator::standardizeSimpleOperator($operator),
            'connector' => $this->standardizeConnector($connector),
        ];

        return $this;
    }

    /**
     * Add a range column filter.
     *
     * @param string      $column    The filter column name.
     * @param array       $values    The filter column values.
     * @param string|bool $operator  The filter operator.
     * @param string|null $connector The filter connector.
     *
     * @throws GrammarException Thrown when the column value is empty.
     *
     * @return self
     */
    protected function addRangeFilter(string $column, array $values, $operator, $connector): self
    {
        if (empty($values)) {
            throw new GrammarException('The filter range condition values can not be empty.');
        }

        $this->filters[] = [
            'compiler'  => 'range',
            'column'    => $column,
            'values'    => $values,
            'count'     => count($values),
            'operator'  => Operator::standardizeRangeOperator($operator),
            'connector' => $this->standardizeConnector($connector),
        ];

        return $this;
    }

    /**
     * Add a null column filter.
     *
     * @param string $column The filter column name.
     * @param bool $operator The filter operator.
     * @param string|null $connector The filter connector.
     *
     * @throws GrammarException
     *
     * @return self
     */
    protected function addNullFilter(string $column, $operator, $connector): self
    {
        $this->filters[] = [
            'compiler'  => 'null',
            'column'    => $column,
            'operator'  => Operator::standardizeNullOperator($operator),
            'connector' => $this->standardizeConnector($connector),
        ];

        return $this;
    }

    /**
     * Compile a simple filter.
     *
     * @param string $column   The filter column.
     * @param string $operator The filter operator.
     *
     * @return string
     */
    protected function compileSimpleFilter(string $column, string $operator): string
    {
        return Util::wrap($column).' '.$operator.' ?';
    }

    /**
     * Compile a range filter.
     *
     * @param string $column   The filter column.
     * @param string $operator The filter operator.
     * @param int    $count    The filter values count.
     *
     * @return string
     */
    protected function compileRangeFilter(string $column, string $operator, int $count): string
    {
        $placeholder = implode(',', array_fill(0, $count, '?'));

        return Util::wrap($column).' '.$operator.' ('.$placeholder.')';
    }

    /**
     * Compile a null filter.
     *
     * @param string $column   The filter column.
     * @param string $operator The filter operator.
     *
     * @return string
     */
    protected function compileNullFilter(string $column, string $operator): string
    {
        return Util::wrap($column).' '.$operator;
    }

    /**
     * Determine if the current filter is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->filters);
    }

    /**
     * Add a column filter.
     *
     * @param string      $column    The filter column name.
     * @param mixed       $value     The filter value.
     * @param string|bool $operator  The filter operator.
     * @param string|null $connector The filter connector.
     *
     * @throws GrammarException Thrown when the column value is invalid.
     *
     * @return self
     */
    public function addColumnFilter(string $column, $value, $operator = true, string $connector = null): self
    {
        if (is_scalar($value)) {
            return $this->addScalarFilter($column, $value, $operator, $connector);
        }

        if (is_iterable($value) || $value instanceof Arrayable) {
            return $this->addRangeFilter($column, Arr::convert($value), $operator, $connector);
        }

        if (is_null($value)) {
            return $this->addNullFilter($column, $operator, $connector);
        }

        throw new GrammarException('The column filter value is invalid.');
    }

    /**
     * Add multiple column filters.
     *
     * @param array $columns The given columns.
     * @param mixed $operator The filter operator.
     * @param string|null $connector The filter connector.
     *
     * @throws GrammarException
     *
     * @return self
     */
    public function addColumnFilters(array $columns, $operator = true, string $connector = null): self
    {
        foreach ($columns as $column => $value) {
            $this->addColumnFilter($column, $value, $operator, $connector);
        }

        return $this;
    }

    /**
     * Add group filter.
     *
     * @param Closure     $builder The filter builder.
     * @param Wrapper     $wrapper The filter wrapper instance.
     * @param string|null $connector The filter connector.
     *
     * @throws GrammarException
     *
     * @return self
     */
    public function addGroupFilter(Closure $builder, Wrapper $wrapper, string $connector = null): self
    {
        // Build a filter group.
        // This wrapper instance must contain a filter instance.
        $builder($wrapper);

        $this->filters[] = [
            'compiler'  => 'group',
            'filter'    => $wrapper->getOriginal(),
            'connector' => $this->standardizeConnector($connector),
        ];

        return $this;
    }

    /**
     * Compile the current instance to a statement string.
     *
     * @param Arguments $arguments The statement binding parameter manager.
     *
     * @return string
     */
    public function compile(Arguments $arguments): string
    {
        $fragments = Fragments::create();

        foreach ($this->filters as $filter) {
            if ('simple' === $filter['compiler']) {
                $fragments->push($filter['connector']);
                $fragments->push($this->compileSimpleFilter($filter['column'], $filter['operator']));
                $arguments->push($filter['value']);
            } elseif ('range' === $filter['compiler']) {
                $fragments->push($filter['connector']);
                $fragments->push($this->compileRangeFilter($filter['column'], $filter['operator'], $filter['count']));
                $arguments->push($filter['values']);
            } elseif ('group' === $filter['compiler']) {
                // Compile only if the filter is not empty.
                if (!$filter['filter']->isEmpty()) {
                    $fragments->push($filter['connector']);
                    $fragments->push(Util::enclose($filter['filter']->compile($arguments)));
                }
            } elseif ('null' === $filter['compiler']) {
                $fragments->push($filter['connector']);
                $fragments->push($this->compileNullFilter($filter['column'], $filter['operator']));
            } else {
                // For unknown types of compilation, do nothing.
                continue;
            }
        }

        // Remove the connector in the first place, because it is redundant.
        $fragments->pop();

        return $fragments->assemble();
    }

    /**
     * Clear all current filters.
     *
     * @return self
     */
    public function clear(): self
    {
        $this->filters = [];

        return $this;
    }

    /**
     * Returns the current filter instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->filters;
    }

    /**
     * Gets the size of the current conditions.
     *
     * @return int
     */
    public function count()
    {
        return count($this->filters);
    }
}
