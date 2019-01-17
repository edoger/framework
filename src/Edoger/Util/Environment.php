<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Util;

class Environment
{
    /**
     * Determine whether the current runtime environment is windows.
     *
     * @return bool
     */
    public static function isWindows(): bool
    {
        return '\\' === DIRECTORY_SEPARATOR;
    }

    /**
     * Determine if the current runtime environment is CLI.
     *
     * @return bool
     */
    public static function isCli(): bool
    {
        return 'cli' === PHP_SAPI;
    }
}
