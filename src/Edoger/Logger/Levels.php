<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger;

use Edoger\Util\Str;
use InvalidArgumentException;

class Levels
{
    const DEBUG     = 100;
    const INFO      = 150;
    const NOTICE    = 200;
    const WARNING   = 250;
    const ERROR     = 300;
    const CRITICAL  = 350;
    const ALERT     = 400;
    const EMERGENCY = 450;

    /**
     * The log level names.
     *
     * @var array
     */
    protected static $names = [
        self::DEBUG     => 'DEBUG',
        self::INFO      => 'INFO',
        self::NOTICE    => 'NOTICE',
        self::WARNING   => 'WARNING',
        self::ERROR     => 'ERROR',
        self::CRITICAL  => 'CRITICAL',
        self::ALERT     => 'ALERT',
        self::EMERGENCY => 'EMERGENCY',
    ];

    /**
     * The log levels.
     *
     * @var array
     */
    protected static $levels = [
        'DEBUG'     => self::DEBUG,
        'INFO'      => self::INFO,
        'NOTICE'    => self::NOTICE,
        'WARNING'   => self::WARNING,
        'ERROR'     => self::ERROR,
        'CRITICAL'  => self::CRITICAL,
        'ALERT'     => self::ALERT,
        'EMERGENCY' => self::EMERGENCY,
    ];

    /**
     * Determines whether the given log level is a log level.
     *
     * @param int $level The given log level.
     *
     * @return bool
     */
    public static function isLevel(int $level): bool
    {
        return isset(static::$names[$level]);
    }

    /**
     * Gets the name of the given log level.
     *
     * @param int $level The given log level.
     *
     * @throws InvalidArgumentException Thrown when the log level is invalid.
     *
     * @return string
     */
    public static function getLevelName(int $level): string
    {
        if (!static::isLevel($level)) {
            throw new InvalidArgumentException('The given log level is invalid.');
        }

        return static::$names[$level];
    }

    /**
     * Determines whether the given string is a log level name.
     *
     * @param string $name The given log level name.
     *
     * @return bool
     */
    public static function isLevelName(string $name): bool
    {
        return isset(static::$levels[Str::upper($name)]);
    }

    /**
     * Gets the name of the given log level.
     *
     * @param string $name The given log level name.
     *
     * @throws InvalidArgumentException Thrown when the log level name is invalid.
     *
     * @return int
     */
    public static function getLevel(string $name): int
    {
        if (!static::isLevelName($name)) {
            throw new InvalidArgumentException('The given log level name is invalid.');
        }

        return static::$levels[$name];
    }
}
