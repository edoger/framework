<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Traits;

use Edoger\Util\Arr;

trait RequestExtrasSupport
{
    /**
     * The extra data.
     *
     * @var array
     */
    protected $extras = [];

    /**
     * Determines whether the current request extra data is empty.
     *
     * @return bool
     */
    public function isEmptyExtras(): bool
    {
        return empty($this->extras);
    }

    /**
     * Determines whether a given key exists in the current request extra data.
     *
     * @param string $key The given key.
     *
     * @return bool
     */
    public function hasExtra(string $key): bool
    {
        return Arr::has($this->extras, $key);
    }

    /**
     * Gets the value of the specified key in the current request extra data.
     *
     * @param string $key     The given key.
     * @param mixed  $default The default value.
     *
     * @return mixed
     */
    public function getExtra(string $key, $default = null)
    {
        return Arr::get($this->extras, $key, $default);
    }

    /**
     * Get all the extra data for the current request.
     *
     * @return array
     */
    public function getExtras(): array
    {
        return $this->extras;
    }

    /**
     * Sets the extra data for the current request.
     *
     * @param string $key   The extra data key.
     * @param mixed  $value The extra data value.
     *
     * @return void
     */
    public function setExtra(string $key, $value): void
    {
        $this->extras[$key] = $value;
    }

    /**
     * Replace all the extra data for the current request.
     *
     * @param mixed $extras The extra data.
     *
     * @return self
     */
    public function replaceExtras($extras)
    {
        $this->extras = Arr::convert($extras);

        return $this;
    }

    /**
     * Delete the extra data for the current request.
     *
     * @param string $key The extra data key.
     *
     * @return void
     */
    public function deleteExtra(string $key): void
    {
        if ($this->hasExtra($key)) {
            unset($this->extras[$key]);
        }
    }

    /**
     * Clear all the extra data for the current request.
     *
     * @return self
     */
    public function clearExtras()
    {
        $this->extras = [];

        return $this;
    }

    /**
     * Gets the size of all the extra data for the current request.
     *
     * @return int
     */
    public function countExtras(): int
    {
        return count($this->extras);
    }
}
