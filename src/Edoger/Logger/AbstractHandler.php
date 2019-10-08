<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger;

use Closure;
use Edoger\Flow\EmptyProcessor;

abstract class AbstractHandler extends EmptyProcessor
{
    /**
     * Handle a log.
     *
     * @param string  $channel The logger channel name.
     * @param Log     $log     The log body instance.
     * @param Closure $next    The trigger for the next log handler.
     *
     * @return bool
     */
    abstract public function handle(string $channel, Log $log, Closure $next): bool;
}
