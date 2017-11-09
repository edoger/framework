<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event\Traits;

use Edoger\Event\Dispatcher;
use Edoger\Event\Event;

trait TriggerSupport
{
    /**
     * Gets the current event dispatcher instance.
     *
     * @return Edoger\Event\Dispatcher
     */
    abstract public function getEventDispatcher(): Dispatcher;

    /**
     * Determines whether the listener for the specified event exists.
     *
     * @param  string    $name The event name.
     * @return boolean
     */
    public function hasEventListener(string $name): bool
    {
        return !$this->getEventDispatcher()->isEmptyListeners($name);
    }

    /**
     * Triggers an event with the specified name.
     *
     * @param  string               $name The event name.
     * @param  mixed                $body The event body.
     * @return Edoger\Event\Event
     */
    public function emit(string $name, $body = []): Event
    {
        return $this->getEventDispatcher()->dispatch($name, $body);
    }
}
