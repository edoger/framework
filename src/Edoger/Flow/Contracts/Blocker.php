<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow\Contracts;

use Throwable;
use Edoger\Container\Container;

interface Blocker
{
    /**
     * Block the current call stack.
     *
     * @param Edoger\Container\Container $input     The processor input parameter container.
     * @param Throwable|null             $exception The captured processor exception.
     *
     * @return mixed
     */
    public function block(Container $input, Throwable $exception = null);
}
