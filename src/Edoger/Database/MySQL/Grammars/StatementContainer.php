<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Grammars;

use Countable;
use ArrayIterator;
use Edoger\Util\Arr;
use RuntimeException;
use IteratorAggregate;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Database\MySQL\Exceptions\GrammarException;

class StatementContainer implements Arrayable, Countable, IteratorAggregate
{
    /**
     * The SQL statement instances.
     *
     * @var array
     */
    protected $statements = [];

    /**
     * The SQL statement options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * The statement container constructor.
     *
     * @param iterable $statements The SQL statement instances.
     *
     * @throws GrammarException
     *
     * @return void
     */
    public function __construct(iterable $statements = [])
    {
        foreach ($statements as $statement) {
            if ($statement instanceof Statement) {
                $this->push($statement);
            } else {
                throw new GrammarException('Invalid statement instance.');
            }
        }
    }

    /**
     * Determine if the current SQL statement instance container is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->statements);
    }

    /**
     * Append SQL statement instance.
     *
     * @param Statement $statement The SQL statement instance.
     *
     * @return self
     */
    public function push(Statement $statement): self
    {
        $this->statements[] = $statement;

        return $this;
    }

    /**
     * Fetch SQL statement instance.
     *
     * @throws RuntimeException Thrown when the statement container is empty.
     *
     * @return Statement
     */
    public function pop(): Statement
    {
        if ($this->isEmpty()) {
            throw new RuntimeException(
                'Can not get statement instance from empty statement container.'
            );
        }

        return array_shift($this->statements);
    }

    /**
     * Determines if the given statement option key exists.
     *
     * @param string $key The given statement option key.
     *
     * @return bool
     */
    public function hasOption(string $key): bool
    {
        return Arr::has($this->options, $key);
    }

    /**
     * Get statement option value.
     *
     * @param string $key     The statement option key.
     * @param mixed  $default The default value.
     *
     * @return mixed
     */
    public function getOption(string $key, $default = null)
    {
        return Arr::get($this->options, $key, $default);
    }

    /**
     * Get all statement options.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Set the statement option.
     *
     * @param string $key   The statement option key.
     * @param mixed  $value The statement option value.
     *
     * @return self
     */
    public function setOption(string $key, $value): self
    {
        $this->options[$key] = $value;

        return $this;
    }

    /**
     * Replace statement options.
     *
     * @param mixed $options The statement options.
     *
     * @return self
     */
    public function replaceOptions($options): self
    {
        $this->options = Arr::convert($options);

        return $this;
    }

    /**
     * Delete statement option.
     *
     * @param string $key The statement option key.
     *
     * @return self
     */
    public function deleteOption(string $key): self
    {
        if ($this->hasOption($key)) {
            unset($this->options[$key]);
        }

        return $this;
    }

    /**
     * Clear all statement options.
     *
     * @return self
     */
    public function clearOptions(): self
    {
        $this->options = [];

        return $this;
    }

    /**
     * Returns the current statement container as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->statements;
    }

    /**
     * Gets the size of the current statement container.
     *
     * @return int
     */
    public function count()
    {
        return count($this->statements);
    }

    /**
     * Gets an iterator instance of the current statement container.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->statements);
    }
}
