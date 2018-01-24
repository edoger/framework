<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL;

use Countable;
use ArrayIterator;
use JsonSerializable;
use IteratorAggregate;
use Edoger\Util\Validator;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Database\MySQL\Exceptions\ArgumentException;

class Arguments implements Arrayable, Countable, IteratorAggregate, JsonSerializable
{
    /**
     * The statement binding parameters.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * The arguments constructor.
     *
     * @param mixed $parameters The statement binding parameters.
     *
     * @return void
     */
    public function __construct($parameters)
    {
        $this->push($parameters);
    }

    /**
     * Create a statement binding parameter collection instance.
     *
     * @param mixed $parameters The statement binding parameters.
     *
     * @return self
     */
    public static function create($parameters = []): self
    {
        return new static($parameters);
    }

    /**
     * Determine whether the current statement binding parameter collection is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->parameters);
    }

    /**
     * Add parameters to the current statement binding parameter collection.
     *
     * @param mixed $parameters The statement binding parameters.
     *
     * @throws Edoger\Database\MySQL\Exceptions\ArgumentException Thrown when the statement binding parameter is invalid.
     *
     * @return self
     */
    public function push($parameters): self
    {
        if (Validator::isStringOrNumber($parameters)) {
            $this->parameters[] = $parameters;
        } elseif (is_iterable($parameters) || $parameters instanceof Arrayable) {
            // An iterable structure sometimes implements the Arrayable interface at the same time,
            // and we convert it first to an array and then traverse it.
            if ($parameters instanceof Arrayable) {
                $parameters = $parameters->toArray();
            }

            foreach ($parameters as $parameter) {
                if (Validator::isStringOrNumber($parameter)) {
                    $this->parameters[] = $parameter;
                } elseif (is_bool($parameter)) {
                    $this->parameters[] = $parameter ? 1 : 0;
                } elseif (is_null($parameter)) {
                    $this->parameters[] = '';
                } else {
                    throw new ArgumentException('Invalid SQL statement binding parameter.');
                }
            }
        } elseif (is_bool($parameters)) {
            $this->parameters[] = $parameters ? 1 : 0;
        } elseif (is_null($parameters)) {
            $this->parameters[] = '';
        } else {
            throw new ArgumentException('Invalid SQL statement binding parameter.');
        }

        return $this;
    }

    /**
     * Clear all parameters from the current statement binding parameter collection.
     *
     * @return self
     */
    public function clear(): self
    {
        $this->parameters = [];

        return $this;
    }

    /**
     * Returns the current statement binding parameter collection as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->parameters;
    }

    /**
     * Gets the size of the current statement binding parameter collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->parameters);
    }

    /**
     * Gets an iterator instance of the current statement binding parameter collection.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->parameters);
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->parameters;
    }
}
