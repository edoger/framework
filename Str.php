<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Util;

class Str
{
    /**
     * This class should not be instantiated.
     */
    private function __construct()
    {
        //
    }
    
    /**
     * Get string length.
     *
     * @param  string    $str The given string.
     * @return integer
     */
    public static function length(string $str): int
    {
        return mb_strlen($str, 'UTF-8');
    }

    /**
     * Returns the uppercase form of the given string.
     *
     * @param  string   $str The given string.
     * @return string
     */
    public static function upper(string $str): string
    {
        return mb_strtoupper($str, 'UTF-8');
    }

    /**
     * Returns the lowercase form of the given string.
     *
     * @param  string   $str The given string.
     * @return string
     */
    public static function lower(string $str): string
    {
        return mb_strtolower($str, 'UTF-8');
    }

    /**
     * Get part of the given string.
     *
     * @param  string   $str    The given string.
     * @param  integer  $start  The start position of the intercept string.
     * @param  integer  $length Maximum number of characters to use from the given string.
     * @return string
     */
    public static function substr(string $str, int $start, int $length = null): string
    {
        return mb_substr($str, $start, $length, 'UTF-8');
    }

    /**
     * Gets the beginning of a given string.
     *
     * @param  string   $str  The given string.
     * @param  integer  $size The maximum size.
     * @return string
     */
    public static function before(string $str, int $size = 1): string
    {
        if ($size <= 0) {
            return '';
        }

        if (static::length($str) <= $size) {
            return $str;
        }

        return static::substr($str, 0, $size);
    }

    /**
     * Gets the end of the given string.
     *
     * @param  string   $str  The given string.
     * @param  integer  $size The maximum size.
     * @return string
     */
    public static function after(string $str, int $size = 1): string
    {
        if ($size <= 0) {
            return '';
        }

        if (static::length($str) <= $size) {
            return $str;
        }

        return static::substr($str, -$size);
    }

    /**
     * Make a string's first character uppercase.
     *
     * @param  string   $str The given string.
     * @return string
     */
    public static function ucfirst(string $str): string
    {
        if (static::length($str) <= 1) {
            return static::upper($str);
        }

        return static::upper(static::substr($str, 0, 1)).static::substr($str, 1);
    }

    /**
     * Find position of first occurrence of string in the given string.
     *
     * @param  string          $str    The given string.
     * @param  string          $needle The string to find in the given string.
     * @param  integer         $offset The search offset.
     * @return integer|false
     */
    public static function strpos(string $str, string $needle, int $offset = 0)
    {
        return mb_strpos($str, $needle, $offset, 'UTF-8');
    }
}
