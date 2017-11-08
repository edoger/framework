<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Util\Contracts;

interface Arrayable
{
    /**
     * Returns the current object as an array.
     *
     * @return array
     */
    public function toArray(): array;
}
