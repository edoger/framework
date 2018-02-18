<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow\Traits;

use RuntimeException;
use Edoger\Container\Store;
use Edoger\Flow\ProcessorQueue;
use Edoger\Flow\Contracts\Processor;

trait FlowProcessorStoreSupport
{
    /**
     * The processor store container.
     *
     * @var Edoger\Container\Store
     */
    protected $store;

    /**
     * Initialize flow processor store support.
     *
     * @return void
     */
    protected function initFlowProcessorStoreSupport(): void
    {
        $this->store = new Store();
    }

    /**
     * Get flow processor store instance.
     *
     * @return Edoger\Container\Store
     */
    protected function getFlowProcessorStore(): Store
    {
        return $this->store;
    }

    /**
     * Create and return a flow processor queue instance.
     *
     * @return Edoger\Flow\ProcessorQueue
     */
    protected function createFlowProcessorQueue(): ProcessorQueue
    {
        return new ProcessorQueue($this->getFlowProcessorStore());
    }

    /**
     * Determines whether the current processor container is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->getFlowProcessorStore()->isEmpty();
    }

    /**
     * Append the given task processor to the current container.
     *
     * @param Edoger\Flow\Contracts\Processor $processor The task processor.
     * @param bool                            $top       Append the processor to the top of the container.
     *
     * @return int
     */
    public function append(Processor $processor, bool $top = false): int
    {
        return $this->getFlowProcessorStore()->append($processor, $top);
    }

    /**
     * Remove a processor from the current container.
     *
     * @param bool $top Remove the processor from the top of the container.
     *
     * @throws RuntimeException Throws when the current processor container is empty.
     *
     * @return Edoger\Flow\Contracts\Processor
     */
    public function remove(bool $top = true): Processor
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('Unable to remove processor from empty processor container.');
        }

        return $this->getFlowProcessorStore()->remove($top);
    }

    /**
     * Clear the current processor container.
     *
     * @return self
     */
    public function clear()
    {
        $this->getFlowProcessorStore()->clear();

        return $this;
    }
}
