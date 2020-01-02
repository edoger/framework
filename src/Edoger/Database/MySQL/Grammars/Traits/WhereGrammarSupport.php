<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Grammars\Traits;

use Closure;
use Edoger\Util\Arr;
use Edoger\Util\Validator;
use Edoger\Container\Wrapper;
use Edoger\Database\MySQL\Grammars\Filter;
use Edoger\Database\MySQL\Exceptions\GrammarException;

trait WhereGrammarSupport
{
    /**
     * Create a where filter wrapper instance.
     *
     * @param string $connector The default filter connector.
     *
     * @return Wrapper
     */
    abstract public function createWhereFilterWrapper(string $connector): Wrapper;

    /**
     * Get the where filter instance.
     *
     * @return Filter
     */
    abstract public function getWhereFilter(): Filter;

    /**
     * Add where filter condition.
     *
     * @param mixed       $column    The filter column.
     * @param mixed       $value     The filter column value.
     * @param bool|string $operator  The filter operator.
     * @param string|null $connector The filter connector.
     *
     * @throws GrammarException Thrown when a parameter is missing.
     * @throws GrammarException Thrown when the filter column is invalid.
     *
     * @return self
     */
    public function where($column, $value = null, $operator = true, string $connector = null)
    {
        if (Validator::isNotEmptyString($column)) {
            if (1 === func_num_args()) {
                // Only one parameter? We require at least two parameters.
                throw new GrammarException('Missing filter column value.');
            } else {
                $this->getWhereFilter()->addColumnFilter($column, $value, $operator, $connector);
            }
        } elseif (is_iterable($column) || $column instanceof Arrayable) {
            $args = func_get_args();

            $this->getWhereFilter()->addColumnFilters(
                Arr::convert($column),
                Arr::get($args, 1, true),
                Arr::get($args, 2)
            );
        } elseif ($column instanceof Closure) {
            $args      = func_get_args();
            $connector = Arr::get($args, 1);

            if (!is_string($connector)) {
                if (is_null($connector)) {
                    $connector = 'and';
                } else {
                    throw new GrammarException('The default filter connector must be a string.');
                }
            }

            $this->getWhereFilter()->addGroupFilter(
                $column,
                $this->createWhereFilterWrapper($connector),
                Arr::get($args, 2)
            );
        } else {
            throw new GrammarException('Invalid filter column.');
        }

        return $this;
    }
}
