<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Logger\Mocks;

use Closure;
use Exception;
use Edoger\Logger\Log;
use Edoger\Logger\AbstractHandler;

class TestExceptionHandler extends AbstractHandler
{
    public function handle(string $channel, Log $log, Closure $next): bool
    {
        throw new Exception('ExceptionHandler');
    }
}
