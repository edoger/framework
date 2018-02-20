<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Config\Mocks;

use Closure;
use Edoger\Config\Repository;
use Edoger\Config\AbstractLoader;

class TestLoader extends AbstractLoader
{
    protected $group;
    protected $items;

    public function __construct(string $group = 'test', array $items = [])
    {
        $this->group = $group;
        $this->items = $items;
    }

    public function load(string $group, bool $reload, Closure $next): Repository
    {
        if ($group === $this->group) {
            return new Repository($this->items);
        }

        return $next();
    }
}
