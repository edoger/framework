<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow;

use Edoger\Container\Queue;

class ProcessorQueue extends Queue
{
    /**
     * Whether all the current processors have been completed.
     *
     * @var bool
     */
    protected $completed = false;

    /**
     * The current processors has thrown an exception.
     *
     * @var bool
     */
    protected $failed = false;

    /**
     * Determine if all the current processors have completed.
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * Mark all the current processors have been completed.
     *
     * @return void
     */
    public function toCompleted(): void
    {
        $this->completed = true;
    }

    /**
     * Determines if the current processors has thrown an exception.
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->failed;
    }

    /**
     * Mark the current processors throws an exception.
     *
     * @return void
     */
    public function toFailed(): void
    {
        $this->failed = true;
    }
}
