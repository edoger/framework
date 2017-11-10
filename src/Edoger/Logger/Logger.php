<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger;

use Edoger\Flow\Flow;
use Edoger\Logger\Handlers\CallableHandler;
use Edoger\Util\Arr;
use InvalidArgumentException;
use RuntimeException;

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
     * @var Edoger\Flow\Flow
     */
    protected $flow;

    /**
     * The lowest processing log level.
     *
     * @var integer
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
     * @param  string  $channel The logger channel name.
     * @param  integer $level   The lowest processing log level.
     * @return void
     */
    public function __construct(string $channel, int $level = Levels::ERROR)
    {
        $this->channel = $channel;
        $this->flow    = new Flow(new Blocker());
        $this->level   = $level;
    }

    /**
     * Get the log handle flow.
     *
     * @return Edoger\Flow\Flow
     */
    protected function getFlow(): Flow
    {
        return $this->flow;
    }

    /**
     * Determines whether the current log handler collection is empty.
     *
     * @return boolean
     */
    public function isEmptyHandlers(): bool
    {
        return $this->getFlow()->isEmpty();
    }

    /**
     * Get the size of the current log handler collection.
     *
     * @return integer
     */
    public function countHandlers(): int
    {
        return $this->getFlow()->count();
    }

    /**
     * Gets the current log handlers.
     *
     * @return array
     */
    public function getHandlers(): array
    {
        // The handler is stored in the form of a stack,
        // and we have to restore the order of their additions.
        return array_reverse($this->getFlow()->toArray());
    }

    /**
     * Append a log handler.
     *
     * @param  Edoger\Logger\AbstractHandler|callable $handler The log handler.
     * @param  boolean                                $top     Append the handler to the top of the stack.
     * @return integer
     */
    public function pushHandler($handler, bool $top = true): int
    {
        if ($handler instanceof AbstractHandler) {
            return $this->getFlow()->append($handler, $top);
        } elseif (is_callable($handler)) {
            return $this->getFlow()->append(new CallableHandler($handler), $top);
        } else {
            throw new InvalidArgumentException('Invalid log handler.');
        }
    }

    /**
     * Delete and return a log handler.
     *
     * @param  boolean                         $top   Remove the handler from the top of the stack.
     * @throws RuntimeException                Throws when the log handle stack is empty.
     * @return Edoger\Logger\AbstractHandler
     */
    public function popHandler(bool $top = true): AbstractHandler
    {
        if ($this->isEmptyHandlers()) {
            throw new RuntimeException(
                'Unable to remove log handler from the empty log handle stack.'
            );
        }

        return $this->getFlow()->remove($top);
    }

    /**
     * Clear the current log handler stack.
     *
     * @return self
     */
    public function clearHandlers(): self
    {
        $this->getFlow()->clear();

        return $this;
    }

    /**
     * Get all captured logs.
     *
     * @param  callable|null $filter The log filter.
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
     * Processes a log at a given log level.
     *
     * @param  integer   $level     The log level.
     * @param  string    $message   The log message.
     * @param  array     $context   The log context.
     * @param  integer   $timestamp The log generation timestamp.
     * @param  mixed     $extra     Additional data for the current log.
     * @return boolean
     */
    public function log(int $level, string $message, array $context = [], int $timestamp = 0, $extra = []): bool
    {
        $log = new Log($level, $message, $context, $timestamp, $extra);

        // Cache the current log.
        $this->logs[] = $log;

        if ($this->isEmptyHandlers()) {
            return false;
        }

        return $this->getFlow()->start(['channel' => $this->channel, 'log' => $log]);
    }
}
