<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Foundation;

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
}
