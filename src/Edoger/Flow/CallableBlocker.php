<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow;

use Throwable;
use Edoger\Container\Wrapper;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Blocker;

class CallableBlocker extends Wrapper implements Blocker
{
    /**
     * The flow blocker wrapper constructor.
     *
     * @param callable $blocker The call stack blocker.
     *
     * @return void
     */
    public function __construct(callable $blocker)
    {
        parent::__construct($blocker);
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
        return call_user_func($this->getOriginal(), $input, $exception);
    }
}
