<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event;

use Edoger\Container\Wrapper;
use Edoger\Event\Contracts\Listener;

class CallableListener extends Wrapper implements Listener
{
    /**
     * The event listener wrapper constructor.
     *
     * @param callable $listener The event listener.
     *
     * @return void
     */
    public function __construct(callable $listener)
    {
        parent::__construct($listener);
    }

    /**
     * Run the current event listener.
     *
     * @param Edoger\Event\Event      $event      The event body.
     * @param Edoger\Event\Dispatcher $dispatcher The event dispatcher.
     *
     * @return void
     */
    public function handle(Event $event, Dispatcher $dispatcher): void
    {
        call_user_func($this->getOriginal(), $event, $dispatcher);
    }
}
