<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Foundation;

use Edoger\Util\Arr;
use Edoger\Database\MySQL\Exceptions\GrammarException;

class Connector
{
    /**
     * Valid filter connectors.
     *
     * @var array
     */
    protected static $connectors = ['and' => 'AND', 'or' => 'OR'];

    /**
     * Determines if the given filter connector is valid.
     *
     * @param string $connector The given filter connector.
     *
     * @return bool
     */
    public static function isValid(string $connector): bool
    {
        return Arr::has(static::$connectors, $connector);
    }

    /**
     * Standardize the given filter connector.
     *
     * @param string $connector The given filter connector.
     *
     * @throws Edoger\Database\MySQL\Exceptions\GrammarException Throws when the filter connector is invalid.
     *
     * @return string
     */
    public static function standardize(string $connector): string
    {
        if (static::isValid($connector)) {
            return static::$connectors[$connector];
        }

        throw new GrammarException(
            sprintf('The given filter connector "%s" is invalid.', $connector)
        );
    }
}
