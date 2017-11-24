<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Container;

use Countable;
use Edoger\Util\Arr;
use RuntimeException;
use IteratorAggregate;
use Edoger\Util\Contracts\Arrayable;

class Queue implements Arrayable, Countable, IteratorAggregate
{
    /**
     * The queue element store.
     *
     * @var Edoger\Containers\Store
     */
    protected $store;

    /**
     * The queue constructor.
     *
     * @param mixed $elements The elements that are added into the queue.
     *
     * @return void
     */
    public function __construct($elements = [])
    {
        $this->store = new Store();

        foreach (Arr::convert($elements) as $element) {
            $this->store->append($element, false);
        }
    }

    /**
     * Determines whether the current queue is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->store->isEmpty();
    }

    /**
     * Append an element to the current queue.
     *
     * @param mixed $element The given element.
     *
     * @return int
     */
    public function enqueue($element): int
    {
        return $this->store->append($element, false);
    }

    /**
     * Removes an element from the current queue.
     *
     * @throws RuntimeException Throws when the current queue is empty.
     *
     * @return mixed
     */
    public function dequeue()
    {
        if ($this->store->isEmpty()) {
            throw new RuntimeException('Unable to remove element from empty queue.');
        }

        return $this->store->remove(true);
    }

    /**
     * Clear the current queue.
     *
     * @return self
     */
    public function clear()
    {
        $this->store->clear();

        return $this;
    }

    /**
     * Returns the current queue as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->store->toArray();
    }

    /**
     * Gets the size of the current queue.
     *
     * @return int
     */
    public function count()
    {
        return $this->store->count();
    }

    /**
     * Gets an iterator instance of the current queue.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return $this->store->getIterator();
    }
}
