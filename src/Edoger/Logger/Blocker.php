<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Logger;

use Throwable;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Blocker as BlockerContract;

class Blocker implements BlockerContract
{
    /**
     * The log handle flow blocker constructor.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Block the current log handle stack.
     *
     * @param Edoger\Container\Container $input     The processor input parameter container.
     * @param Throwable|null             $exception The captured processor exception.
     *
     * @return bool
     */
    public function block(Container $input, Throwable $exception = null)
    {
        return $exception ? false : true;
    }
}
