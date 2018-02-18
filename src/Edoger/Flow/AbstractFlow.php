<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow;

use Closure;
use Countable;
use Throwable;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Blocker;
use Edoger\Flow\Contracts\Processor;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Flow\Traits\FlowBlockerSupport;
use Edoger\Flow\Traits\FlowProcessorStoreSupport;

abstract class AbstractFlow implements Arrayable, Countable
{
    use FlowBlockerSupport, FlowProcessorStoreSupport;

    /**
     * The abstract flow constructor.
     *
     * @param Edoger\Flow\Contracts\Blocker $blocker The flow blocker.
     *
     * @return void
     */
    public function __construct(Blocker $blocker)
    {
        $this->initFlowBlockerSupport($blocker);
        $this->initFlowProcessorStoreSupport();
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
        $input  = new Container($input);
        $queue  = $this->createFlowProcessorQueue();
        $result = $this->run($queue, new Container($input), true);

        // If the process handler has completed or thrown an exception,
        // we will immediately return the result.
        // Otherwise, the current flow is blocked and the system will
        // immediately trigger the blocker block event.
        if ($queue->isCompleted() || $queue->isFailed()) {
            return $result;
        } else {
            return $this->getFlowBlocker()->block($input, $result);
        }
    }

    /**
     * Run the task in the processing queue.
     *
     * @param Edoger\Flow\ProcessorQueue $queue The processor queue.
     * @param Edoger\Container\Container $input The processor input parameter container.
     * @param bool                       $top   Whether it is the top call stack.
     *
     * @return mixed
     */
    protected function run(ProcessorQueue $queue, Container $input, bool $top = false)
    {
        if ($queue->isEmpty()) {
            // Mark all the current processors have been completed.
            $queue->toCompleted();

            return $this->getFlowBlocker()->complete($input);
        }

        if ($top) {
            // Captures an exception only in the topmost call stack.
            // This will introduce an additional recursive call.
            // Any exception thrown in the processor will be passed to the blocker.
            // We will not catch any blocker exceptions.
            try {
                return $this->run($queue, $input);
            } catch (Throwable $exception) {
                // Mark the current processors throws an exception.
                $queue->toFailed();

                return $this->getFlowBlocker()->error($input, $exception);
            }
        }

        return $this->doProcess($queue->dequeue(), $input, function () use ($queue, $input) {
            return $this->run($queue, $input);
        });
    }

    /**
     * Get the current processor container as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->getFlowProcessorStore()->toArray();
    }

    /**
     * Gets the size of the current processor container.
     *
     * @return int
     */
    public function count()
    {
        return $this->getFlowProcessorStore()->count();
    }

    /**
     * Run the flow processor.
     *
     * @param Edoger\Flow\Contracts\Processor $processor The flow processor.
     * @param Edoger\Container\Container      $input     The processor input parameter container.
     * @param Closure                         $next      The trigger for the next processor.
     *
     * @return mixed
     */
    abstract protected function doProcess(Processor $processor, Container $input, Closure $next);
}
