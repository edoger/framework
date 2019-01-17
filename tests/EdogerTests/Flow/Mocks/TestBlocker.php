<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Flow\Mocks;

use Throwable;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Blocker;

class TestBlocker implements Blocker
{
    public function block(Container $input, $result)
    {
        return ['block', $input->toArray(), $result];
    }

    public function complete(Container $input)
    {
        return ['complete', $input->toArray(), null];
    }

    public function error(Container $input, Throwable $exception)
    {
        return ['error', $input->toArray(), get_class($exception), $exception->getMessage()];
    }
}
