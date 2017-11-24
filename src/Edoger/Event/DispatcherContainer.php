<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event;

use Edoger\Container\Wrapper;
use Edoger\Event\Contracts\DispatcherContainer as DispatcherContainerContract;

class DispatcherContainer extends Wrapper implements DispatcherContainerContract
{
    /**
     * The event dispatcher container constructor.
     *
     * @param Edoger\Event\Dispatcher $dispatcher The event dispatcher.
     *
     * @return void
     */
    public function __construct(Dispatcher $dispatcher)
    {
        parent::__construct($dispatcher);
    }

    /**
     * Gets the current event dispatcher instance.
     *
     * @return Edoger\Event\Dispatcher
     */
    public function getEventDispatcher(): Dispatcher
    {
        return $this->getOriginal();
    }
}
