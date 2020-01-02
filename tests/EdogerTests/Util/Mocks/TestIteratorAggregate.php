<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Util\Mocks;

use ArrayIterator;
use IteratorAggregate;

class TestIteratorAggregate implements IteratorAggregate
{
    protected $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }
}
