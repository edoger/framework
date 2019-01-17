<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Globals;

use Edoger\Http\Foundation\Collection;

class Server extends Collection
{
    /**
     * Create server and execution environment variables collection.
     *
     * @param iterable $server Server and execution environment variables.
     *
     * @return self
     */
    public static function create(iterable $server): self
    {
        return new static($server);
    }
}
