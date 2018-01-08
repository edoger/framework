<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow\Tests\Support;

use Throwable;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Blocker;

class TestReturnExceptionBlocker implements Blocker
{
    public function block(Container $input, Throwable $exception = null)
    {
        return $exception;
    }
}
