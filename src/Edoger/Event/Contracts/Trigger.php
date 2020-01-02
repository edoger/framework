<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event\Contracts;

use Edoger\Event\Event;

interface Trigger
{
    /**
     * Determines whether the listener for the specified event exists.
     *
     * @param string $name The event name.
     *
     * @return bool
     */
    public function hasEventListener(string $name): bool;

    /**
     * Triggers an event with the specified name.
     *
     * @param string $name The event name.
     * @param mixed  $body The event body.
     *
     * @return Event
     */
    public function emit(string $name, $body = []): Event;
}
