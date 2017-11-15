<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger\Tests\Support;

use Edoger\Logger\Contracts\Formatter;
use Edoger\Logger\Log;

class TestFormatter implements Formatter
{
    public function format(string $channel, Log $log): string
    {
        return 'TEST::'.$channel.$log->getMessage();
    }
}