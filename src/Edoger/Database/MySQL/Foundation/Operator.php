<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Foundation;

use Edoger\Util\Arr;
use Edoger\Database\MySQL\Exceptions\GrammarException;

class Operator
{
    /**
     * Valid filter simple operators.
     *
     * @var array
     */
    protected static $operators = [
        '='         => '=',
        'eq'        => '=',
        '!='        => '!=',
        '<>'        => '!=',
        'neq'       => '!=',
        '<'         => '<',
        'lt'        => '<',
        '<='        => '<=',
        'lte'       => '<=',
        '>'         => '>',
        'gt'        => '>',
        '>='        => '>=',
        'gte'       => '>=',
        '?='        => 'LIKE',
        'like'      => 'LIKE',
        '!?='       => 'NOT LIKE',
        'notlike'   => 'NOT LIKE',
        '~='        => 'REGEXP',
        'regexp'    => 'REGEXP',
        '!~='       => 'NOT REGEXP',
        'notregexp' => 'NOT REGEXP',
    ];

    /**
     * Standardize the given filter simple operator.
     *
     * @param string|bool $operator The given filter simple operator.
     *
     * @throws GrammarException Throws when the filter simple operator is invalid.
     *
     * @return string
     */
    public static function standardizeSimpleOperator($operator): string
    {
        // This boolean usually derives from the default value of the parameter.
        // We should first check and format it.
        if (is_bool($operator)) {
            return $operator ? '=' : '!=';
        }

        if (is_string($operator) && Arr::has(static::$operators, $operator)) {
            return static::$operators[$operator];
        }

        throw new GrammarException('The given filter simple operator is invalid.');
    }

    /**
     * Standardize the given filter range operator.
     *
     * @param string|bool $operator The given filter range operator.
     *
     * @throws GrammarException Throws when the filter range operator is invalid.
     *
     * @return string
     */
    public static function standardizeRangeOperator($operator): string
    {
        if (is_bool($operator)) {
            return $operator ? 'IN' : 'NOT IN';
        }

        if ('in' === $operator) {
            return 'IN';
        }

        if ('notin' === $operator) {
            return 'NOT IN';
        }

        throw new GrammarException('The given filter range operator is invalid.');
    }

    /**
     * Standardize the given filter null operator.
     *
     * @param bool $operator The given filter null operator.
     *
     * @throws GrammarException Throws when the filter null operator is invalid.
     *
     * @return string
     */
    public static function standardizeNullOperator($operator): string
    {
        if (is_bool($operator)) {
            return $operator ? 'IS NULL' : 'IS NOT NULL';
        }

        throw new GrammarException('The given filter null operator is invalid.');
    }
}
