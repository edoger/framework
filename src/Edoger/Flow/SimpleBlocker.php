<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow;

use Throwable;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Blocker;

class SimpleBlocker implements Blocker
{
    /**
     * Handle the flow block event.
     *
     * @param Container $input  The processor input parameter container.
     * @param mixed     $result The processor flow return value.
     *
     * @return mixed
     */
    public function block(Container $input, $result)
    {
        return new Container(['input' => $input, 'result' => $result]);
    }

    /**
     * Handle the flow complete event.
     *
     * @param Container $input The processor input parameter container.
     *
     * @return mixed
     */
    public function complete(Container $input)
    {
        return new Container(['input' => $input]);
    }

    /**
     * Handle the flow error event.
     *
     * @param Container $input     The processor input parameter container.
     * @param Throwable                  $exception The captured flow processor exception.
     *
     * @return mixed
     */
    public function error(Container $input, Throwable $exception)
    {
        return new Container(['input' => $input, 'exception' => $exception]);
    }
}
