<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
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
     * @param Container  $input  The processor input parameter container.
     * @param Repository $result The processor flow return value.
     *
     * @return Repository
     */
    public function block(Container $input, $result)
    {
        return $result;
    }

    /**
     * Handle the flow complete event.
     *
     * @param Container $input The processor input parameter container.
     *
     * @return Repository
     */
    public function complete(Container $input)
    {
        return new Repository();
    }

    /**
     * Handle the flow error event.
     *
     * @param Container $input     The processor input parameter container.
     * @param Throwable $exception The captured flow processor exception.
     *
     * @return Repository
     */
    public function error(Container $input, Throwable $exception)
    {
        return new Repository();
    }
}
