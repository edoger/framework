<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event;

use Edoger\Container\Collection;

class Event extends Collection
{
    /**
     * The event name.
     *
     * @var string
     */
    protected $name;

    /**
     * The event group name.
     *
     * @var string
     */
    protected $group;

    /**
     * Marks whether the current event has been interrupted.
     *
     * @var bool
     */
    protected $interrupted = false;

    /**
     * The event constructor.
     *
     * @param string $name  The event name.
     * @param string $group The event group name.
     * @param mixed  $body  The event body.
     *
     * @return void
     */
    public function __construct(string $name, string $group = '', $body = [])
    {
        $this->group = $group;
        $this->name  = $name;

        parent::__construct($body);
    }

    /**
     * Gets the event name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets the event group name.
     *
     * @return string
     */
    public function getGroupName(): string
    {
        return $this->group;
    }

    /**
     * Gets the event full name.
     *
     * @return string
     */
    public function getFullName(): string
    {
        if ('' === $this->group) {
            return $this->name;
        }

        return $this->group.'.'.$this->name;
    }

    /**
     * Determines whether the current event has been interrupted.
     *
     * @return bool
     */
    public function isInterrupted(): bool
    {
        return $this->interrupted;
    }

    /**
     * Interrupt the current event.
     *
     * @return self
     */
    public function interrupt()
    {
        $this->interrupted = true;

        return $this;
    }
}
