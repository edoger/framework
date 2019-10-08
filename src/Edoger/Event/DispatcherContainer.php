<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event;

use Edoger\Event\Contracts\DispatcherContainer as DispatcherContainerContract;

class DispatcherContainer implements DispatcherContainerContract
{
    /**
     * The event dispatcher.
     *
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * The subcomponent event name.
     *
     * @var string
     */
    protected $subcomponentEventName;

    /**
     * The event dispatcher container constructor.
     *
     * @param Dispatcher $dispatcher            The event dispatcher.
     * @param string     $subcomponentEventName The subcomponent event name.
     *
     * @return void
     */
    public function __construct(Dispatcher $dispatcher, string $subcomponentEventName = '')
    {
        $this->dispatcher            = $dispatcher;
        $this->subcomponentEventName = $subcomponentEventName;
    }

    /**
     * Gets the current event dispatcher instance.
     *
     * @return Dispatcher
     */
    public function getEventDispatcher(): Dispatcher
    {
        return $this->dispatcher;
    }

    /**
     * Get the current subcomponent event name.
     *
     * @return string
     */
    public function getSubcomponentEventName(): string
    {
        return $this->subcomponentEventName;
    }

    /**
     * Standardize the gievn event name.
     *
     * @param string $name The gievn event name.
     *
     * @return string
     */
    protected function standardizeEventName(string $name): string
    {
        // Automatically add subcomponent event name.
        if ('' !== $sub = $this->getSubcomponentEventName()) {
            return $sub.'.'.$name;
        }

        return $name;
    }
}
