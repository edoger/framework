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

class Util
{
    /**
     * Use back quote to wrap the given string.
     *
     * @param string $value The given string.
     *
     * @return string
     */
    public static function wrap(string $value): string
    {
        return '`'.$value.'`';
    }

    /**
     * Constructs a standard matrix from the given multi-column values.
     *
     * @param iterable $columns The given multi-column values.
     *
     * @return array
     */
    public static function matrixize(iterable $columns): array
    {
        $counts = [];

        // Determine the number of rows for each column.
        // We need to determine the boundaries of the matrix based on the maximum number of rows.
        foreach ($columns as $column => $values) {
            $counts[$column] = count($values);
        }

        // If there is no column, return empty matrix.
        if (empty($counts)) {
            return [];
        }

        $max    = max($counts);
        $matrix = [];

        foreach ($columns as $column => $values) {
            if ($counts[$column] < $max) {
                $matrix[$column] = array_pad($values, $max, Arr::last($values));
            } else {
                $matrix[$column] = $values;
            }
        }

        return $matrix;
    }

    /**
     * Transpose a given column matrix to a row matrix.
     *
     * @param array $matrix The given matrix.
     *
     * @return array
     */
    public static function transpose(array $matrix): array
    {
        // If the given matrix is an empty matrix, nothing will be done and
        // an empty array will be returned.
        if (empty($matrix)) {
            return [];
        }

        // Here, we will not check the validity of the matrix.
        // We will assume that the given matrix is a standard one.
        return array_map(null, ...Arr::values($matrix));
    }

    /**
     * Stitch the given field names as a string.
     *
     * @param array $fields The given field names.
     * @param bool  $wrap   Whether to automatically wrap the column name.
     *
     * @return string
     */
    public static function columnize(array $fields, $wrap = true): string
    {
        if ($wrap) {
            return implode(',', array_map([static::class, 'wrap'], $fields));
        }

        return implode(',', $fields);
    }

    /**
     * Use parentheses to wrap the given string.
     *
     * @param string $value The given string.
     *
     * @return string
     */
    public static function enclose(string $value): string
    {
        return '('.$value.')';
    }
}
