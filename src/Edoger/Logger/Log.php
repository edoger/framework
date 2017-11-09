<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger;

use Edoger\Util\Arr;

class Log
{
    /**
     * Whether the current log has been handled.
     *
     * @var boolean
     */
    protected $handled = false;

    /**
     * The log level.
     *
     * @var integer
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
     * @var integer
     */
    protected $timestamp;

    /**
     * Additional data for the current log.
     *
     * @var array
     */
    protected $extra;

    /**
     * The log constructor.
     *
     * @param  integer $level     The log level.
     * @param  string  $message   The log message.
     * @param  array   $context   The log context.
     * @param  integer $timestamp The log generation timestamp.
     * @param  mixed   $extra     Additional data for the current log.
     * @return void
     */
    public function __construct(int $level, string $message, array $context = [], int $timestamp = 0, $extra = [])
    {
        $this->level     = $level;
        $this->message   = $message;
        $this->context   = $context;
        $this->timestamp = $timestamp > 0 ? $timestamp : time();
        $this->extra     = Arr::convert($extra);
    }

    /**
     * Determines whether the current log has been handled.
     *
     * @return boolean
     */
    public function isHandled(): bool
    {
        return $this->handled;
    }

    /**
     * Mark the current log as handled.
     *
     * @return self
     */
    public function toHandled()
    {
        $this->handled = true;

        return $this;
    }

    /**
     * Gets the current log level.
     *
     * @return integer
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
        return Levels::getLevelName($this->level);
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
     * @return integer
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * Gets the current log generation datetime.
     *
     * @param  string   $format The format of the outputted date string.
     * @return string
     */
    public function getDatetime(string $format = 'Y-m-d H:i:s'): string
    {
        return date($format, $this->timestamp);
    }

    /**
     * Determines whether the current log extra data is empty.
     *
     * @return boolean
     */
    public function isEmptyExtras(): bool
    {
        return empty($this->extra);
    }

    /**
     * Determines whether a given key exists in the current log extra data.
     *
     * @param  string    $key The given key.
     * @return boolean
     */
    public function hasExtra(string $key): bool
    {
        return Arr::has($this->extra, $key);
    }

    /**
     * Gets the value of the specified key in the current log extra data.
     *
     * @param  string  $key     The given key.
     * @param  mixed   $default The default value.
     * @return mixed
     */
    public function getExtra(string $key, $default = null)
    {
        return Arr::get($this->extra, $key, $default);
    }

    /**
     * Get all the extra data for the current log.
     *
     * @return array
     */
    public function getExtras(): array
    {
        return $this->extra;
    }

    /**
     * Sets the extra data for the current log.
     *
     * @param  string $key   The extra data key.
     * @param  mixed  $value The extra data value.
     * @return void
     */
    public function setExtra(string $key, $value): void
    {
        $this->extra[$key] = $value;
    }

    /**
     * Replace all the extra data for the current log.
     *
     * @param  mixed  $extra The extra data.
     * @return self
     */
    public function replaceExtras($extra)
    {
        $this->extra = Arr::convert($extra);

        return $this;
    }

    /**
     * Delete the extra data for the current log.
     *
     * @param  string $key The extra data key.
     * @return void
     */
    public function deleteExtra(string $key): void
    {
        if ($this->hasExtra($key)) {
            unset($this->extra[$key]);
        }
    }

    /**
     * Clear all the extra data for the current log.
     *
     * @return self
     */
    public function clearExtras()
    {
        $this->extra = [];

        return $this;
    }

    /**
     * Gets the size of all the extra data for the current log.
     *
     * @return integer
     */
    public function countExtras(): int
    {
        return count($this->extra);
    }
}
