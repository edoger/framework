<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config\Tests\Support;

use Closure;
use Edoger\Config\AbstractLoader;
use Edoger\Config\Repository;
use Exception;

class TestExceptionLoader extends AbstractLoader
{
    public function load(string $group, Closure $next): Repository
    {
        throw new Exception($group);
    }
}
