<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Serializer\Tests\Support;

use Edoger\Serializer\JsonSerializer;

class DisabledJsonSerializer extends JsonSerializer
{
    public static function isEnabled(): bool
    {
        return false;
    }
}
