<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Globals;

use Edoger\Http\Foundation\Collection;

class Query extends Collection
{
    /**
     * Create request query parameters collection.
     *
     * @param  iterable $server The request query parameters.
     * @return self
     */
    public static function create(iterable $query): self
    {
        return new static($query);
    }

    /**
     * Create request query parameters collection from $_GET.
     *
     * @return self
     */
    public static function createFromGlobals(): self
    {
        return static::create($_GET);
    }
}
