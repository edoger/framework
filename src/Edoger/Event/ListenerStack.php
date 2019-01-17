<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event;

use Edoger\Container\Stack;

class ListenerStack extends Stack
{
    /**
     * Marks whether the current event listener stack is enabled.
     *
     * @var bool
     */
    protected $enabled = true;

    /**
     * Determines whether the current event listener stack is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Enable the current event listener stack.
     *
     * @return self
     */
    public function enable()
    {
        $this->enabled = true;

        return $this;
    }

    /**
     * Disable the current event listener stack.
     *
     * @return self
     */
    public function disable()
    {
        $this->enabled = false;

        return $this;
    }
}
