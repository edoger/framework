<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow;

use Closure;
use Throwable;
use Edoger\Container\Store;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Flow;
use Edoger\Flow\Contracts\Blocker;
use Edoger\Flow\Contracts\Processor;

abstract class AbstractFlow implements Flow
{
    /**
     * The flow blocker.
     *
     * @var Blocker
     */
    protected $blocker;

    /**
     * The flow processor store.
     *
     * @var Store
     */
    protected $store;

    /**
     * The abstract flow constructor.
     *
     * @param Blocker $blocker The flow blocker.
     *
     * @return void
     */
    public function __construct(Blocker $blocker)
    {
        $this->blocker = $blocker;
        $this->store   = new Store();
    }

    /**
     * Get current flow blocker.
     *
     * @return Blocker
     */
    protected function getBlocker(): Blocker
    {
        return $this->blocker;
    }

    /**
     * Get flow processor store instance.
     *
     * @return Store
     */
    protected function getStore(): Store
    {
        return $this->store;
    }

    /**
     * Create and return a flow processor queue instance.
     *
     * @return ProcessorQueue
     */
    protected function createQueue(): ProcessorQueue
    {
        return new ProcessorQueue($this->getStore());
    }

    /**
     * Run flow processors.
     *
     * @param ProcessorQueue $queue The processor queue.
     * @param Container      $input The processor input parameter container.
     * @param bool           $top   Whether it is the top call stack.
     *
     * @return mixed
     */
    protected function run(ProcessorQueue $queue, Container $input, bool $top = false)
    {
        // If the current processor is the first one in the queue, we need to catch any
        // exceptions that may be thrown. To do this, we will additionally introduce a
        // recursive call.
        if ($top) {
            try {
                return $this->run($queue, $input);
            } catch (Throwable $exception) {
                // If any one processor throws an exception, we all need to mark the
                // queue as failed.
                $queue->toFailed();

                return $this->getBlocker()->error($input, $exception);
            }
        }

        if ($queue->isEmpty()) {
            // If the processor queue is already empty,then we think all the processors
            // have completed. Set the processor queue status to completed.
            $queue->toCompleted();

            return $this->getBlocker()->complete($input);
        }

        return $this->doProcess($queue->dequeue(), $input, function () use ($queue, $input) {
            return $this->run($queue, $input);
        });
    }

    /**
     * Start the current flow processor queue.
     *
     * @param mixed $input The processor parameters.
     *
     * @return mixed
     */
    public function start($input = [])
    {
        $input = new Container($input);
        $queue = $this->createQueue();

        // Run the flow processors.
        $result = $this->run($queue, $input, true);

        // If the processor has completed or thrown an exception, we will immediately return
        // the result. Otherwise, the current flow is blocked and the system will immediately
        // trigger the blocker block event.
        if (!$queue->isCompleted() && !$queue->isFailed()) {
            try {
                $result = $this->getBlocker()->block($input, $result);
            } catch (Throwable $exception) {
                // If the flow blocker's blocking event handler throws an exception, we also
                // call the blocker's erroneous event handler.
                return $this->getBlocker()->error($input, $exception);
            }
        }

        return $result;
    }

    /**
     * Run the flow processor.
     *
     * @param Processor $processor The flow processor.
     * @param Container $input     The processor input parameter container.
     * @param Closure                         $next      The trigger for the next processor.
     *
     * @return mixed
     */
    abstract protected function doProcess(Processor $processor, Container $input, Closure $next);
}
