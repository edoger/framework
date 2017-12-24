<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Util;

class Validator
{
    /**
     * Determines if the given value is a non-empty string.
     *
     * @param mixed $value The given value.
     *
     * @return bool
     */
    public static function isNotEmptyString($value): bool
    {
        return is_string($value) && '' !== $value;
    }

    /**
     * Determines if the given value is a non-empty array.
     *
     * @param mixed $value The given value.
     *
     * @return bool
     */
    public static function isNotEmptyArray($value): bool
    {
        return is_array($value) && !empty($value);
    }

    /**
     * Determines if the given value is a numeric value.
     * Notice: Any string will not be considered a numeric value.
     *
     * @param mixed $value The given value.
     *
     * @return bool
     */
    public static function isNumber($value): bool
    {
        return is_int($value) || is_float($value);
    }

    /**
     * Determines if the given value is a number or a string.
     *
     * @param mixed $value The given value.
     *
     * @return bool
     */
    public static function isStringOrNumber($value): bool
    {
        return is_string($value) || is_numeric($value);
    }

    /**
     * Determines if the given value is a positive number.
     *
     * @param mixed $value The given value.
     *
     * @return bool
     */
    public static function isPositiveNumber($value): bool
    {
        return static::isNumber($value) && $value > 0;
    }

    /**
     * Determines if the given value is a positive integer.
     *
     * @param mixed $value The given value.
     *
     * @return bool
     */
    public static function isPositiveInteger($value): bool
    {
        return is_int($value) && $value > 0;
    }

    /**
     * Determines if the given value is a negative number.
     *
     * @param mixed $value The given value.
     *
     * @return bool
     */
    public static function isNegativeNumber($value): bool
    {
        return static::isNumber($value) && $value < 0;
    }

    /**
     * Determines if the given value is a negative integer.
     *
     * @param mixed $value The given value.
     *
     * @return bool
     */
    public static function isNegativeInteger($value): bool
    {
        return is_int($value) && $value < 0;
    }

    /**
     * Determine if the given value is an IPv4 address.
     *
     * @param mixed $value    The given value.
     * @param bool  $private  Whether or not to allow for private address.
     * @param bool  $reserved Whether or not to allow for the reserved address.
     *
     * @return bool
     */
    public static function isIpv4($value, bool $private = false, bool $reserved = false): bool
    {
        if (!static::isNotEmptyString($value)) {
            return false;
        }

        $flags = FILTER_FLAG_IPV4;

        if ($private) {
            $flags |= FILTER_FLAG_NO_PRIV_RANGE;
        }

        if ($reserved) {
            $flags |= FILTER_FLAG_NO_RES_RANGE;
        }

        return false !== filter_var($value, FILTER_VALIDATE_IP, $flags);
    }

    /**
     * Determine if the given value is an IPv6 address.
     *
     * @param mixed $value    The given value.
     * @param bool  $private  Whether or not to allow for private address.
     * @param bool  $reserved Whether or not to allow for the reserved address.
     *
     * @return bool
     */
    public static function isIpv6($value, bool $private = false, bool $reserved = false): bool
    {
        if (!static::isNotEmptyString($value)) {
            return false;
        }

        $flags = FILTER_FLAG_IPV6;

        if ($private) {
            $flags |= FILTER_FLAG_NO_PRIV_RANGE;
        }

        if ($reserved) {
            $flags |= FILTER_FLAG_NO_RES_RANGE;
        }

        return false !== filter_var($value, FILTER_VALIDATE_IP, $flags);
    }

    /**
     * Determine if the given value is an email address.
     *
     * @param mixed $value The given value.
     *
     * @return bool
     */
    public static function isEmail($value): bool
    {
        if (!static::isNotEmptyString($value)) {
            return false;
        }

        return false !== filter_var($value, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE);
    }

    /**
     * Determines if the given value is an integer string.
     *
     * @param mixed $value The given value.
     *
     * @return bool
     */
    public static function isIntergerString($value): bool
    {
        if (!static::isNotEmptyString($value)) {
            return false;
        }

        return (bool) preg_match('/^[1-9]\d*$/', $value);
    }

    /**
     * Determine if the given value is a mobile phone number.
     *
     * @param mixed $value The given value.
     *
     * @return bool
     */
    public static function isMobileNumber($value): bool
    {
        if (!static::isNotEmptyString($value)) {
            return false;
        }

        return (bool) preg_match('/^1\d{10}$/', $value);
    }

    /**
     * Determine if the given value is a tel number.
     *
     * @param mixed $value The given value.
     *
     * @return bool
     */
    public static function isTelNumber($value): bool
    {
        if (!static::isNotEmptyString($value)) {
            return false;
        }

        return (bool) preg_match('/^\d+(?:\-\d+)*$/', $value);
    }

    /**
     * Determine if the given value is a domain name.
     *
     * @param mixed $value The given value.
     *
     * @return bool
     */
    public static function isDomainName($value): bool
    {
        if (!static::isNotEmptyString($value)) {
            return false;
        }

        return (bool) preg_match('/^[a-z\d\-]+(?:\.[a-z\d\-]+)+$/', $value);
    }

    /**
     * Determine whether a given value is an attribute name.
     *
     * @param mixed $value The given value.
     *
     * @return bool
     */
    public static function isAttributeName($value): bool
    {
        if (!static::isNotEmptyString($value)) {
            return false;
        }

        return (bool) preg_match('/^[a-zA-Z_]\w*$/', $value);
    }
}
