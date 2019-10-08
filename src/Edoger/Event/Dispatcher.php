<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event;

use Edoger\Util\Arr;
use Edoger\Util\Str;
use InvalidArgumentException;
use Edoger\Event\Contracts\Listener;
use Edoger\Util\Contracts\Arrayable;

class Dispatcher implements Arrayable
{
    /**
     * Already registered event listeners.
     *
     * @var array
     */
    protected $listeners = [];

    /**
     * The event group name.
     *
     * @var string
     */
    protected $group;

    /**
     * The event dispatcher constructor.
     *
     * @param mixed  $listeners The event listeners.
     * @param string $group     The event group name.
     *
     * @return void
     */
    public function __construct($listeners = [], string $group = '')
    {
        $this->group = $group;

        // This allows the event dispatcher to mirror another instance of the event dispatcher.
        foreach (Arr::convert($listeners) as $name => $value) {
            foreach (Arr::wrap($value) as $listener) {
                $this->addListener($name, $listener);
            }
        }
    }

    /**
     * Gets the current event group name.
     *
     * @return string
     */
    public function getEventGroupName(): string
    {
        return $this->group;
    }

    /**
     * Standardize the name of the event.
     *
     * @param string $name The event name.
     *
     * @return string
     */
    public function standardizeEventName(string $name): string
    {
        if ('' === $this->group) {
            return $name;
        }

        return $this->group.'.'.$name;
    }

    /**
     * Determines whether the current event listener stack is enabled.
     *
     * @param string $name The event name.
     *
     * @return bool
     */
    public function isEnabled(string $name): bool
    {
        if ($stack = Arr::get($this->listeners, $this->standardizeEventName($name))) {
            return $stack->isEnabled();
        }

        return false;
    }

    /**
     * Enable the current event listener stack.
     *
     * @param string $name The event name.
     *
     * @return self
     */
    public function enable(string $name)
    {
        if ($stack = Arr::get($this->listeners, $this->standardizeEventName($name))) {
            $stack->enable();
        }

        return $this;
    }

    /**
     * Disable the current event listener stack.
     *
     * @param string $name The event name.
     *
     * @return self
     */
    public function disable(string $name)
    {
        if ($stack = Arr::get($this->listeners, $this->standardizeEventName($name))) {
            $stack->disable();
        }

        return $this;
    }

    /**
     * Determines whether the event listener stack for the specified event is empty.
     *
     * @param string $name The event name.
     *
     * @return bool
     */
    public function isEmptyListeners(string $name): bool
    {
        if ($stack = Arr::get($this->listeners, $this->standardizeEventName($name))) {
            return $stack->isEmpty();
        }

        // If the event listener does not exist, TRUE is returned.
        return true;
    }

    /**
     * Gets the event listeners for the specified event.
     *
     * @param string $name The event name.
     *
     * @return array
     */
    public function getListeners(string $name): array
    {
        if ($stack = Arr::get($this->listeners, $this->standardizeEventName($name))) {
            return array_map(
                function ($listener) {
                    if ($listener instanceof CallableListener) {
                        return $listener->getOriginal();
                    }

                    return $listener;
                },
                // The order of the returned listeners is the same as the order of addition.
                array_reverse($stack->toArray())
            );
        }

        // If the event listener does not exist, an empty array is returned.
        return [];
    }

    /**
     * Add an event listener for the specified event.
     *
     * @param string            $name     The event name.
     * @param Listener|callable $listener The event listener.
     *
     * @throws InvalidArgumentException Thrown when the event listener is invalid.
     *
     * @return int
     */
    public function addListener(string $name, $listener): int
    {
        $name = $this->standardizeEventName($name);

        // If the listener stack for the specified event name does not exist,
        // we will automatically create it.
        if (!Arr::has($this->listeners, $name)) {
            $this->listeners[$name] = new ListenerStack();
        }

        if ($listener instanceof Listener) {
            return $this->listeners[$name]->push($listener);
        } elseif (is_callable($listener)) {
            return $this->listeners[$name]->push(new CallableListener($listener));
        } else {
            // The listener must be a callable structure
            // or an object that implements the Edoger\Event\Contracts\Listener interface.
            throw new InvalidArgumentException('Invalid event listener.');
        }
    }

    /**
     * Removes a listener for the specified event.
     *
     * @param string $name The event name.
     *
     * @return Listener|callable|null
     */
    public function removeListener(string $name)
    {
        // If the listener for the specified event name does not exist or is empty, NULL is returned.
        if ($this->isEmptyListeners($name)) {
            return null;
        }

        $listener = $this->listeners[$this->standardizeEventName($name)]->pop();

        if ($listener instanceof CallableListener) {
            return $listener->getOriginal();
        }

        return $listener;
    }

    /**
     * Clear all listeners for the specified event.
     *
     * @param string $name The event name.
     *
     * @return self
     */
    public function clearListeners(string $name)
    {
        if ($stack = Arr::get($this->listeners, $this->standardizeEventName($name))) {
            $stack->clear();
        }

        return $this;
    }

    /**
     * Gets the listener stack size for the specified event.
     *
     * @param string $name The event name.
     *
     * @return int
     */
    public function countListeners(string $name): int
    {
        if ($stack = Arr::get($this->listeners, $this->standardizeEventName($name))) {
            return $stack->count();
        }

        // If the event listener does not exist, 0 is returned.
        return 0;
    }

    /**
     * Distribute events, run the event listener program.
     *
     * @param string $name The event name.
     * @param mixed  $body The event body.
     *
     * @return Event
     */
    public function dispatch(string $name, $body = []): Event
    {
        $event = new Event($name, $this->group, $body);

        if (!$this->isEmptyListeners($name)) {
            // The event listener stack.
            $stack = $this->listeners[$this->standardizeEventName($name)];

            foreach ($stack as $listener) {
                // If the event is interrupted, the event loop will be exited immediately.
                // If the event is disabled, the event loop is immediately exited.
                if ($event->isInterrupted() || !$stack->isEnabled()) {
                    break;
                }

                $listener->handle($event, $this);
            }
        }

        return $event;
    }

    /**
     * Returns the current event dispatcher as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array  = [];
        $length = Str::length($this->group);

        foreach ($this->listeners as $name => $stack) {
            // If the event group name exists, remove the event group name from the event name.
            if ($length) {
                $name = Str::substr($name, $length + 1);
            }

            // Since the event listener is stored as a stack, the order of the listener is restored
            // here to ensure that the event listener runs in a strict order during the replication
            // of the event dispatcher.
            $array[$name] = array_map(
                function ($listener) {
                    if ($listener instanceof CallableListener) {
                        return $listener->getOriginal();
                    }

                    return $listener;
                },
                array_reverse($stack->toArray())
            );
        }

        return $array;
    }
}
