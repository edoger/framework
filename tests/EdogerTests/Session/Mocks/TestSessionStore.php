<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Session\Mocks;

use Edoger\Session\Contracts\SessionStore;

class TestSessionStore implements SessionStore
{
    protected $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function get(string $key, $default = null)
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    public function getAll(): array
    {
        return $this->data;
    }

    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function delete(string $key): void
    {
        if ($this->has($key)) {
            unset($this->data[$key]);
        }
    }

    public function clear(): void
    {
        $this->data = [];
    }

    public function getData(): array
    {
        return $this->data;
    }
}
