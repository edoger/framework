<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event\Traits;

use Edoger\Event\Event;
use Edoger\Event\Dispatcher;

trait TriggerSupport
{
    /**
     * Gets the current event dispatcher instance.
     *
     * @return Dispatcher
     */
    abstract public function getEventDispatcher(): Dispatcher;

    /**
     * Get the current subcomponent event name.
     *
     * @return string
     */
    abstract public function getSubcomponentEventName(): string;

    /**
     * Standardize the gievn event name.
     *
     * @param string $name The gievn event name.
     *
     * @return string
     */
    abstract protected function standardizeEventName(string $name): string;

    /**
     * Determines whether the listener for the specified event exists.
     *
     * @param string $name The event name.
     *
     * @return bool
     */
    public function hasEventListener(string $name): bool
    {
        return !$this->getEventDispatcher()->isEmptyListeners($this->standardizeEventName($name));
    }

    /**
     * Triggers an event with the specified name.
     *
     * @param string $name The event name.
     * @param mixed  $body The event body.
     *
     * @return Event
     */
    public function emit(string $name, $body = []): Event
    {
        return $this->getEventDispatcher()->dispatch($this->standardizeEventName($name), $body);
    }
}
