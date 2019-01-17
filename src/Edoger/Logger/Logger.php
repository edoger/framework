<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger;

use Edoger\Util\Arr;
use RuntimeException;
use InvalidArgumentException;
use Edoger\Logger\Handlers\CallableHandler;

class Logger
{
    /**
     * The logger channel name.
     *
     * @var string
     */
    protected $channel;

    /**
     * The log handle flow.
     *
     * @var Edoger\Logger\Flow
     */
    protected $flow;

    /**
     * The lowest processing log level.
     *
     * @var int
     */
    protected $level;

    /**
     * All captured logs.
     *
     * @var array
     */
    protected $logs = [];

    /**
     * The logger constructor.
     *
     * @param string $channel The logger channel name.
     * @param int    $level   The lowest processing log level.
     *
     * @return void
     */
    public function __construct(string $channel, int $level = Levels::DEBUG)
    {
        $this->channel = $channel;
        $this->flow    = new Flow(new Blocker());
        $this->level   = $level;
    }

    /**
     * Get the log handle flow.
     *
     * @return Edoger\Logger\Flow
     */
    protected function getHandleFlow(): Flow
    {
        return $this->flow;
    }

    /**
     * Determines whether the current log handler collection is empty.
     *
     * @return bool
     */
    public function isEmptyHandlers(): bool
    {
        return $this->getHandleFlow()->isEmpty();
    }

    /**
     * Get the size of the current log handler collection.
     *
     * @return int
     */
    public function countHandlers(): int
    {
        return $this->getHandleFlow()->count();
    }

    /**
     * Gets the current log handlers.
     *
     * @return array
     */
    public function getHandlers(): array
    {
        $handlers = $this->getHandleFlow()->toArray();

        if (empty($handlers)) {
            return $handlers;
        }

        // The handler is stored in the form of a stack,
        // and we have to restore the order of their additions.
        return array_reverse($handlers);
    }

    /**
     * Append a log handler.
     *
     * @param Edoger\Logger\AbstractHandler|callable $handler The log handler.
     * @param bool                                   $top     Append the handler to the top of the stack.
     *
     * @return int
     */
    public function pushHandler($handler, bool $top = true): int
    {
        if ($handler instanceof AbstractHandler) {
            return $this->getHandleFlow()->append($handler, $top);
        }

        // For a callable structure, we wrap it as a "Edoger\Logger\Handlers\CallableHandler"
        // instance, and we do not automatically restore it when we get it.
        if (is_callable($handler)) {
            return $this->getHandleFlow()->append(new CallableHandler($handler), $top);
        }

        throw new InvalidArgumentException('Invalid log handler.');
    }

    /**
     * Delete and return a log handler.
     *
     * @param bool $top Remove the handler from the top of the stack.
     *
     * @throws RuntimeException Throws when the log handle stack is empty.
     *
     * @return Edoger\Logger\AbstractHandler
     */
    public function popHandler(bool $top = true): AbstractHandler
    {
        if ($this->isEmptyHandlers()) {
            throw new RuntimeException(
                'Unable to remove log handler from the empty log handle stack.'
            );
        }

        return $this->getHandleFlow()->remove($top);
    }

    /**
     * Clear the current log handler stack.
     *
     * @return self
     */
    public function clearHandlers(): self
    {
        $this->getHandleFlow()->clear();

        return $this;
    }

    /**
     * Record a log of a given log level.
     *
     * @param int    $level   The log level.
     * @param string $message The log message.
     * @param array  $context The log context.
     *
     * @return bool
     */
    public function log(int $level, string $message, array $context = []): bool
    {
        if ($level < $this->level) {
            return false;
        }

        $log = new Log($level, $message, $context);

        // Cache the current log.
        $this->logs[] = $log;

        return $this->getHandleFlow()->start(['channel' => $this->channel, 'log' => $log]);
    }

    /**
     * Get all captured logs.
     *
     * @param callable|null $filter The log filter.
     *
     * @return array
     */
    public function getLogs(callable $filter = null): array
    {
        if (empty($this->logs)) {
            return [];
        }

        if (is_null($filter)) {
            return $this->logs;
        }

        return Arr::values(array_filter($this->logs, $filter, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * Record a "DEBUG" level log.
     *
     * @param string $message The log message.
     * @param array  $context The log context.
     *
     * @return bool
     */
    public function debug(string $message, array $context = []): bool
    {
        return $this->log(Levels::DEBUG, $message, $context);
    }

    /**
     * Record a "INFO" level log.
     *
     * @param string $message The log message.
     * @param array  $context The log context.
     *
     * @return bool
     */
    public function info(string $message, array $context = []): bool
    {
        return $this->log(Levels::INFO, $message, $context);
    }

    /**
     * Record a "NOTICE" level log.
     *
     * @param string $message The log message.
     * @param array  $context The log context.
     *
     * @return bool
     */
    public function notice(string $message, array $context = []): bool
    {
        return $this->log(Levels::NOTICE, $message, $context);
    }

    /**
     * Record a "WARNING" level log.
     *
     * @param string $message The log message.
     * @param array  $context The log context.
     *
     * @return bool
     */
    public function warning(string $message, array $context = []): bool
    {
        return $this->log(Levels::WARNING, $message, $context);
    }

    /**
     * Record a "ERROR" level log.
     *
     * @param string $message The log message.
     * @param array  $context The log context.
     *
     * @return bool
     */
    public function error(string $message, array $context = []): bool
    {
        return $this->log(Levels::ERROR, $message, $context);
    }

    /**
     * Record a "CRITICAL" level log.
     *
     * @param string $message The log message.
     * @param array  $context The log context.
     *
     * @return bool
     */
    public function critical(string $message, array $context = []): bool
    {
        return $this->log(Levels::CRITICAL, $message, $context);
    }

    /**
     * Record a "ALERT" level log.
     *
     * @param string $message The log message.
     * @param array  $context The log context.
     *
     * @return bool
     */
    public function alert(string $message, array $context = []): bool
    {
        return $this->log(Levels::ALERT, $message, $context);
    }

    /**
     * Record a "EMERGENCY" level log.
     *
     * @param string $message The log message.
     * @param array  $context The log context.
     *
     * @return bool
     */
    public function emergency(string $message, array $context = []): bool
    {
        return $this->log(Levels::EMERGENCY, $message, $context);
    }
}
