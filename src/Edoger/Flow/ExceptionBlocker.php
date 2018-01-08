<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow;

use Throwable;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Blocker;

class ExceptionBlocker implements Blocker
{
    /**
     * The flow exception blocker constructor.
     *
     * @return void
     */
    public function __construct()
    {
        // do nothing
    }

    /**
     * Block the current flow.
     *
     * @param Edoger\Container\Container $input     The processor input parameter container.
     * @param Throwable|null             $exception The captured processor exception.
     *
     * @return mixed
     */
    public function block(Container $input, Throwable $exception = null)
    {
        // By default, the system will return the captured exception.
        return $exception;
    }
}
