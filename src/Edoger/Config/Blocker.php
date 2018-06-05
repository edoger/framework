<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config;

use Throwable;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Blocker as BlockerContract;

class Blocker implements BlockerContract
{
    /**
     * Handle the flow block event.
     *
     * @param Edoger\Container\Container $input  The processor input parameter container.
     * @param Edoger\Config\Repository   $result The processor flow return value.
     *
     * @return Edoger\Config\Repository
     */
    public function block(Container $input, $result)
    {
        return $result;
    }

    /**
     * Handle the flow complete event.
     *
     * @param Edoger\Container\Container $input The processor input parameter container.
     *
     * @return Edoger\Config\Repository
     */
    public function complete(Container $input)
    {
        return new Repository();
    }

    /**
     * Handle the flow error event.
     *
     * @param Edoger\Container\Container $input     The processor input parameter container.
     * @param Throwable                  $exception The captured flow processor exception.
     *
     * @return Edoger\Config\Repository
     */
    public function error(Container $input, Throwable $exception)
    {
        return new Repository();
    }
}
