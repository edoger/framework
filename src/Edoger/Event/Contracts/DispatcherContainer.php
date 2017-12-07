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

interface DispatcherContainer
{
    /**
     * Gets the current event dispatcher instance.
     *
     * @return Edoger\Event\Dispatcher
     */
    public function getEventDispatcher(): Dispatcher;

    /**
     * Get the current subcomponent event name.
     *
     * @return string
     */
    public function getSubcomponentEventName(): string;
}
