<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger;

use Closure;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Processor;

abstract class AbstractHandler implements Processor
{
    /**
     * Process the current task.
     *
     * @param Edoger\Container\Container $input The processor input parameter container.
     * @param Closure                    $next  The trigger for the next processor.
     *
     * @return mixed
     */
    final public function process(Container $input, Closure $next)
    {
        return $this->handle($input->get('channel'), $input->get('log'), $next);
    }

    /**
     * Handle a log.
     *
     * @param string            $channel The logger channel name.
     * @param Edoger\Logger\Log $log     The log body instance.
     * @param Closure           $next    The trigger for the next log handler.
     *
     * @return bool
     */
    abstract public function handle(string $channel, Log $log, Closure $next): bool;
}
