<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Grammars;

use Edoger\Util\Arr;
use Edoger\Database\MySQL\Arguments;

class SQLStatement
{
    /**
     * The statement binding parameter manager.
     *
     * @var Edoger\Database\MySQL\Arguments
     */
    protected $arguments;

    /**
     * The SQL statement.
     *
     * @var string
     */
    protected $statement = '';

    /**
     * The SQL statement options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * The SQL statement constructor.
     *
     * @param Edoger\Database\MySQL\Arguments|null $arguments The statement binding parameter manager.
     *
     * @return void
     */
    public function __construct(Arguments $arguments = null)
    {
        if (is_null($arguments)) {
            $this->arguments = Arguments::create();
        } else {
            $this->arguments = $arguments;
        }
    }

    /**
     * Get the statement binding parameter manager.
     *
     * @return Edoger\Database\MySQL\Arguments
     */
    public function getArguments(): Arguments
    {
        return $this->arguments;
    }

    /**
     * Get the SQL statement.
     *
     * @return string
     */
    public function getStatement(): string
    {
        return $this->statement;
    }

    /**
     * Set the SQL statement.
     *
     * @param string $statement The SQL statement.
     *
     * @return self
     */
    public function setStatement(string $statement): self
    {
        $this->statement = $statement;

        return $this;
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
}
