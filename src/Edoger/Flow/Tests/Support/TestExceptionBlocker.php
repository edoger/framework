<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow\Tests\Support;

use Exception;
use Throwable;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Blocker;

class TestExceptionBlocker implements Blocker
{
    public function block(Container $input, Throwable $exception = null)
    {
        if (is_null($exception)) {
            throw new Exception('BlockerException');
        }

        throw $exception;
    }
}
