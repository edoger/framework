<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Serializer\Contracts;

interface Serializer
{
    /**
     * Serialize the given value into a string.
     *
     * @param mixed $value The given value.
     *
     * @return string
     */
    public function serialize($value): string;

    /**
     * Deserialize the given string.
     *
     * @param string $str The given string.
     *
     * @return mixed
     */
    public function deserialize(string $str);
}
