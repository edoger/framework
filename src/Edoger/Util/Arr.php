<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Util;

use Traversable;
use Edoger\Util\Contracts\Arrayable;

class Arr
{
    /**
     * Determines whether a given key exists in a given array.
     *
     * @param array  $arr The given array.
     * @param string $key The given key name.
     *
     * @return bool
     */
    public static function has(array $arr, string $key): bool
    {
        return array_key_exists($key, $arr);
    }

    /**
     * Gets an element from a given array.
     *
     * @param array  $arr     An array.
     * @param string $key     Key name.
     * @param mixed  $default The default value.
     *
     * @return mixed
     */
    public static function get(array $arr, string $key, $default = null)
    {
        return static::has($arr, $key) ? $arr[$key] : $default;
    }

    /**
     * Gets the first element of the array.
     *
     * @param array $arr     An array.
     * @param mixed $default The default value.
     *
     * @return mixed
     */
    public static function first(array $arr, $default = null)
    {
        return empty($arr) ? $default : reset($arr);
    }

    /**
     * Gets the last element of the array.
     *
     * @param array $arr     An array.
     * @param mixed $default The default value.
     *
     * @return mixed
     */
    public static function last(array $arr, $default = null)
    {
        return empty($arr) ? $default : end($arr);
    }

    /**
     * Wraps the given value into an array.
     *
     * @param mixed       $value The given value.
     * @param string|null $name  The given name. Valid when the given value is not an array.
     *
     * @return array
     */
    public static function wrap($value, string $name = null): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_null($value)) {
            return [];
        }

        return is_null($name) ? [$value] : [$name => $value];
    }

    /**
     * Converts a given value to an array.
     *
     * @param mixed $value The given value.
     *
     * @return array
     */
    public static function convert($value): array
    {
        if (is_array($value)) {
            return $value;
        } elseif ($value instanceof Arrayable) {
            return $value->toArray();
        } elseif ($value instanceof Traversable) {
            return iterator_to_array($value);
        } else {
            return (array) $value;
        }
    }

    /**
     * Gets all the keys from the given array.
     *
     * @param array $arr The given array.
     *
     * @return array
     */
    public static function keys(array $arr): array
    {
        return array_keys($arr);
    }

    /**
     * Gets all the values from the given array and indexes the array numerically.
     *
     * @param array $arr The given array.
     *
     * @return array
     */
    public static function values(array $arr): array
    {
        return array_values($arr);
    }

    /**
     * Append elements to a given array.
     *
     * @param array $arr    The given array.
     * @param mixed $values The given elements.
     *
     * @return array
     */
    public static function append(array $arr, $values): array
    {
        foreach (static::wrap($values) as $value) {
            $arr[] = $value;
        }

        return $arr;
    }

    /**
     * Determines whether the given array is an associative array.
     *
     * @param array $arr The given array.
     *
     * @return bool
     */
    public static function isAssoc(array $arr): bool
    {
        return empty($arr) || static::keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Merge two given arrays.
     *
     * @param array $original The original array.
     * @param array $values   The array to be merged.
     *
     * @return array
     */
    public static function merge(array $original, array $values): array
    {
        foreach ($values as $key => $value) {
            $original[$key] = $value;
        }

        return $original;
    }
}
