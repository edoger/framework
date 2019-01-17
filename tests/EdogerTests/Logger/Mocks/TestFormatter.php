<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Logger\Mocks;

use Edoger\Logger\Log;
use Edoger\Logger\Contracts\Formatter;

class TestFormatter implements Formatter
{
    public function format(string $channel, Log $log): string
    {
        return 'TEST::'.$channel.$log->getMessage();
    }
}
