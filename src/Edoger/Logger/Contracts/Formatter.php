<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger\Contracts;

use Edoger\Logger\Log;

interface Formatter
{
    /**
     * Format the contents of the log.
     *
     * @param string $channel The logger channel name.
     * @param Log    $log     The log body instance.
     *
     * @return string
     */
    public function format(string $channel, Log $log): string;
}
