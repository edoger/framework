<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Util;

use Closure;
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
     * Determines if any of the given multiple keys exist in the given array.
     *
     * @param array       $arr  The given array.
     * @param iterable    $keys The given keys.
     * @param string|null &$hit The hit key.
     *
     * @return bool
     */
    public static function hasAny(array $arr, iterable $keys, string &$hit = null): bool
    {
        foreach ($keys as $key) {
            if (static::has($arr, $key)) {
                $hit = $key;

                return true;
            }
        }

        return false;
    }

    /**
     * Determines if each key in a given array exists in a given array.
     *
     * @param array       $arr     The given array.
     * @param iterable    $keys    The given keys.
     * @param string|null &$missed The missed key.
     *
     * @return bool
     */
    public static function hasEvery(array $arr, iterable $keys, string &$missed = null): bool
    {
        foreach ($keys as $key) {
            if (!static::has($arr, $key)) {
                $missed = $key;

                return false;
            }
        }

        return true;
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
     * Gets any one of the elements in the given array within the given range.
     *
     * @param array       $arr     The given array.
     * @param iterable    $keys    The given keys.
     * @param mixed       $default The default value.
     * @param string|null &$hit    The hit key.
     *
     * @return mixed
     */
    public static function getAny(array $arr, iterable $keys, $default = null, string &$hit = null)
    {
        foreach ($keys as $key) {
            if (static::has($arr, $key)) {
                $hit = $key;

                return $arr[$key];
            }
        }

        return $default;
    }

    /**
     * Query an element from the given array.
     *
     * @param array  $arr     The given array.
     * @param string $name    The query key name.
     * @param mixed  $default The default value.
     *
     * @return mixed
     */
    public static function query(array $arr, string $name, $default = null)
    {
        // If there is a query key in the given array, the value is returned immediately.
        if (static::has($arr, $name)) {
            return $arr[$name];
        }

        // If the given name is not a query key, the default value is returned.
        if (1 === count($queries = explode('.', $name))) {
            return $default;
        }

        $result = $arr;

        foreach ($queries as $query) {
            if (is_array($result) && static::has($result, $query)) {
                $result = $result[$query];
            } else {
                return $default;
            }
        }

        return $result;
    }

    /**
     * Use the given handler to iterate through each element of the given array.
     *
     * @param array    $arr       The given array.
     * @param callable $handler   The given array element handler.
     * @param mixed    $parameter Additional parameter for the handler.
     *
     * @return array
     */
    public static function each(array $arr, callable $handler, $parameter = null): array
    {
        // Converting to closure can support handler reference parameters.
        $handler = Closure::fromCallable($handler);
        $handled = [];

        foreach ($arr as $key => $value) {
            if (true === $handler($value, $key, $parameter)) {
                // Save the handled key and value.
                // The key may be modified in the handler.
                $handled[$key] = $value;
            }
        }

        return $handled;
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

    /**
     * Determines if the given array is a one-dimensional array.
     *
     * @param array $arr The given array.
     *
     * @return bool
     */
    public static function isOneDimensional(array $arr): bool
    {
        if (empty($arr)) {
            return true;
        }

        foreach ($arr as $value) {
            if (is_array($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Converts the given XML string to an array.
     * If the conversion fails will return an empty array.
     *
     * @param string $xml The given XML string.
     *
     * @return array
     */
    public static function convertFromXml(string $xml): array
    {
        if ($obj = @simplexml_load_string($xml)) {
            $arr = json_decode(json_encode($obj), true);

            if (is_array($arr)) {
                return $arr;
            }
        }

        return [];
    }

    /**
     * Slices the given array by the specified size.
     *
     * @param array $values The given array.
     * @param int   $size   The size of the slice.
     *
     * @return array
     */
    public static function slice(array $values, int $size = 1): array
    {
        if ($size < 1 || empty($values)) {
            return [];
        }

        $count  = count($values);
        $slices = [];

        for ($i = 0; $i < $count; $i += $size) {
            $slices[] = array_slice($values, $i, $size);
        }

        return $slices;
    }
}
