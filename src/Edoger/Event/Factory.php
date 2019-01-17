<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event;

class Factory
{
    /**
     * Create an event dispatcher instance.
     *
     * @param string $group     The event group name.
     * @param mixed  $listeners The event listeners collection.
     *
     * @return Edoger\Event\Dispatcher
     */
    public static function createDispatcher(string $group = '', $listeners = []): Dispatcher
    {
        return new Dispatcher($listeners, $group);
    }

    /**
     * Create a Edoger event dispatcher instance.
     *
     * @param string $group     The event group name.
     * @param mixed  $listeners The event listeners collection.
     *
     * @return Edoger\Event\Dispatcher
     */
    public static function createEdogerDispatcher(string $group = '', $listeners = []): Dispatcher
    {
        if ('' === $group) {
            $group = 'edoger';
        } else {
            $group = 'edoger.'.$group;
        }

        return static::createDispatcher($group, $listeners);
    }
}
