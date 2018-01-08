<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger\Formatter;

use Edoger\Logger\Log;
use Edoger\Logger\Contracts\Formatter;

class LineFormatter implements Formatter
{
    /**
     * The format of the outputted date string.
     *
     * @var string
     */
    protected $dateFormat;

    /**
     * Whether to automatically append newline.
     *
     * @var bool
     */
    protected $linefeed;

    /**
     * The log line formatter constructor.
     *
     * @param string $dateFormat The format of the outputted date string.
     * @param bool   $linefeed   Whether to automatically append newline.
     *
     * @return void
     */
    public function __construct(string $dateFormat = 'Y-m-d H:i:s', bool $linefeed = true)
    {
        $this->dateFormat = $dateFormat;
        $this->linefeed   = $linefeed;
    }

    /**
     * Format the contents of the log.
     *
     * @param string            $channel The logger channel name.
     * @param Edoger\Logger\Log $log     The log body instance.
     *
     * @return string
     */
    public function format(string $channel, Log $log): string
    {
        $formatted = sprintf(
            '[%s][%s][%s] %s',
            $channel,
            $log->getDatetime($this->dateFormat),
            $log->getLevelName(),
            $log->getMessage()
        );

        if ($this->linefeed) {
            return $formatted.PHP_EOL;
        }

        return $formatted;
    }
}
