<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Cache\Tests\Support;

use Edoger\Cache\Drivers\ApcuDriver;

class DisabledApcuDriver extends ApcuDriver
{
    public static function isEnabled(): bool
    {
        return false;
    }
}
