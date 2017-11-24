<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow;

use Countable;
use Throwable;
use RuntimeException;
use Edoger\Container\Queue;
use Edoger\Container\Store;
use InvalidArgumentException;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Blocker;
use Edoger\Flow\Contracts\Processor;
use Edoger\Util\Contracts\Arrayable;

class Flow implements Arrayable, Countable
{
    /**
     * The flow blocker.
     *
     * @var Edoger\Flow\Contracts\Blocker
     */
    protected $blocker;

    /**
     * The processor container.
     *
     * @var Edoger\Container\Store
     */
    protected $container;

    /**
     * The flow constructor.
     *
     * @param Edoger\Flow\Contracts\Blocker|callable $blocker The flow blocker.
     *
     * @throws InvalidArgumentException Throws when the flow blocker is invalid.
     *
     * @return void
     */
    public function __construct($blocker)
    {
        if ($blocker instanceof Blocker) {
            $this->blocker = $blocker;
        } elseif (is_callable($blocker)) {
            $this->blocker = new CallableBlocker($blocker);
        } else {
            throw new InvalidArgumentException('Invalid flow blocker.');
        }

        $this->container = new Store();
    }

    /**
     * Determines whether the current processor container is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->container->isEmpty();
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
        return $this->container->append($processor, $top);
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

        return $this->container->remove($top);
    }

    /**
     * Clear the current processor container.
     *
     * @return self
     */
    public function clear()
    {
        $this->container->clear();

        return $this;
    }

    /**
     * Start the current task processor queue.
     *
     * @param mixed $input The processor parameter list.
     *
     * @return mixed
     */
    public function start($input = [])
    {
        return $this->run(new Queue($this->container), new Container($input), true);
    }

    /**
     * Get the current processor container.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->container->toArray();
    }

    /**
     * Gets the size of the current processor container.
     *
     * @return int
     */
    public function count()
    {
        return $this->container->count();
    }

    /**
     * Run the task in the processing queue.
     *
     * @param Edoger\Container\Queue     $queue The processor queue.
     * @param Edoger\Container\Container $input The processor input parameter container.
     * @param bool                       $top   Whether it is the top call stack.
     *
     * @return mixed
     */
    protected function run(Queue $queue, Container $input, bool $top)
    {
        if ($queue->isEmpty()) {
            return $this->blocker->block($input);
        }

        // Captures an exception only in the topmost call stack.
        if ($top) {
            try {
                // This will introduce an additional recursive call.
                return $this->run($queue, $input, false);
            } catch (Throwable $exception) {
                // Any exception thrown in the processor will be passed to the blocker.
                // We will not catch any blocker exceptions.
                return $this->blocker->block($input, $exception);
            }
        }

        // Process the current task.
        return $queue->dequeue()->process($input, function () use ($queue, $input) {
            // Recursive call.
            return $this->run($queue, $input, false);
        });
    }
}
