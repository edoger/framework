<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger\Handlers;

use Closure;
use Edoger\Logger\AbstractHandler;
use Edoger\Logger\Log;
use RuntimeException;

class CallableHandler extends AbstractHandler
{
    /**
     * The log callable handler.
     *
     * @var callable
     */
    protected $handler;

    /**
     * The callable handler constructor.
     *
     * @param  callable $handler The log callable handler.
     * @return void
     */
    public function __construct(callable $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Get the log callable handler.
     *
     * @return callable
     */
    public function getHandler(): callable
    {
        return $this->handler;
    }

    /**
     * Handle a log.
     *
     * @param  Edoger\Logger\Log $log  The log body instance.
     * @param  Closure           $next The trigger for the next log handler.
     * @return boolean
     */
    public function handle(Log $log, Closure $next): bool
    {
        $return = call_user_func($this->getHandler(), $log, $next);

        if (!is_bool($return)) {
            throw new RuntimeException(
                'The log callable handler must return a boolean value.'
            );
        }

        return $return;
    }
}
