<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger;

use Edoger\Util\Contracts\Arrayable;

class Log implements Arrayable
{
    /**
     * The log level.
     *
     * @var int
     */
    protected $level;

    /**
     * The log message.
     *
     * @var string
     */
    protected $message;

    /**
     * The log context.
     *
     * @var array
     */
    protected $context;

    /**
     * The log generation timestamp.
     *
     * @var int
     */
    protected $timestamp;

    /**
     * The log constructor.
     *
     * @param int    $level   The log level.
     * @param string $message The log message.
     * @param array  $context The log context.
     *
     * @return void
     */
    public function __construct(int $level, string $message, array $context = [])
    {
        $this->level     = $level;
        $this->message   = $message;
        $this->context   = $context;
        $this->timestamp = time();
    }

    /**
     * Gets the current log level.
     *
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * Gets the current log level name.
     *
     * @return string
     */
    public function getLevelName(): string
    {
        return Levels::getLevelName($this->getLevel());
    }

    /**
     * Gets the current log message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Gets the current log context.
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Gets the current log generation timestamp.
     *
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * Gets the current log generation datetime.
     *
     * @param string $format The format of the outputted date string.
     *
     * @return string
     */
    public function getDatetime(string $format = 'Y-m-d H:i:s'): string
    {
        return date($format, $this->timestamp);
    }

    /**
     * Returns the current log instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'level'     => $this->getLevel(),
            'levelName' => $this->getLevelName(),
            'message'   => $this->getMessage(),
            'timestamp' => $this->getTimestamp(),
            'context'   => $this->getContext(),
        ];
    }
}
