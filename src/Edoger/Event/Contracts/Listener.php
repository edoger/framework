<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event\Contracts;

use Edoger\Event\Dispatcher;
use Edoger\Event\Event;

interface Listener
{
    /**
     * Run the current event listener.
     *
     * @param  Edoger\Event\Event      $event      The event body.
     * @param  Edoger\Event\Dispatcher $dispatcher The event dispatcher.
     * @return void
     */
    public function handle(Event $event, Dispatcher $dispatcher): void;
}
