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

class StatusBlocker implements Blocker
{
    /**
     * Return value when processing failed.
     */
    const STATUS_FAILURE = 0;

    /**
     * The return value when processing is successful.
     */
    const STATUS_SUCCESS = 1;

    /**
     * The flow status blocker constructor.
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
        return $exception ? static::STATUS_FAILURE : static::STATUS_SUCCESS;
    }
}
