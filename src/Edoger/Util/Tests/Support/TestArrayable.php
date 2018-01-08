<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Util\Tests\Support;

use Edoger\Util\Contracts\Arrayable;

class TestArrayable implements Arrayable
{
    protected $arr;

    public function __construct(array $arr = [])
    {
        $this->arr = $arr;
    }

    public function toArray(): array
    {
        return $this->arr;
    }
}
