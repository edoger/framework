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

class Stack implements Arrayable, Countable, IteratorAggregate
{
    /**
     * The stack element store.
     *
     * @var Edoger\Containers\Store
     */
    protected $store;

    /**
     * The stack constructor.
     *
     * @param mixed $elements The elements that are added into the stack.
     *
     * @return void
     */
    public function __construct($elements = [])
    {
        $this->store = new Store();

        foreach (Arr::convert($elements) as $element) {
            $this->store->append($element, true);
        }
    }

    /**
     * Determines whether the current stack is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->store->isEmpty();
    }

    /**
     * Add an element to the current stack.
     *
     * @param mixed $element The given element.
     *
     * @return int
     */
    public function push($element): int
    {
        return $this->store->append($element, true);
    }

    /**
     * Removes an element from the current stack.
     *
     * @throws RuntimeException Throws when the current stack is empty.
     *
     * @return mixed
     */
    public function pop()
    {
        if ($this->store->isEmpty()) {
            throw new RuntimeException('Unable to remove element from empty stack.');
        }

        return $this->store->remove(true);
    }

    /**
     * Clear the current stack.
     *
     * @return self
     */
    public function clear(): self
    {
        $this->store->clear();

        return $this;
    }

    /**
     * Returns the current stack as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->store->toArray();
    }

    /**
     * Gets the size of the current stack.
     *
     * @return int
     */
    public function count()
    {
        return $this->store->count();
    }

    /**
     * Gets an iterator instance of the current stack.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return $this->store->getIterator();
    }
}
