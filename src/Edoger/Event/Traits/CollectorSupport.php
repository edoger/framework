<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event\Traits;

use Edoger\Event\Dispatcher;

trait CollectorSupport
{
    /**
     * Gets the current event dispatcher instance.
     *
     * @return Edoger\Event\Dispatcher
     */
    abstract public function getEventDispatcher(): Dispatcher;

    /**
     * Get the current subcomponent event name.
     *
     * @return string
     */
    abstract public function getSubcomponentEventName(): string;

    /**
     * Add an event listener for the specified event.
     *
     * @param string                                   $name     The event name.
     * @param Edoger\Event\Contracts\Listener|callable $listener The event listener.
     *
     * @return self
     */
    public function on(string $name, $listener)
    {
        // Automatically add subcomponent event name.
        if ('' !== $subcomponentEventName = $this->getSubcomponentEventName()) {
            $name = $subcomponentEventName.'.'.$name;
        }

        $this->getEventDispatcher()->addListener($name, $listener);

        return $this;
    }
}
