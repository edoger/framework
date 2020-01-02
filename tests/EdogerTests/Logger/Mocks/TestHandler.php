<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Logger\Mocks;

use Closure;
use Edoger\Logger\Log;
use Edoger\Logger\AbstractHandler;

class TestHandler extends AbstractHandler
{
    protected $return;

    public function __construct(bool $return = true)
    {
        $this->return = $return;
    }

    public function handle(string $channel, Log $log, Closure $next): bool
    {
        return $this->return;
    }
}
