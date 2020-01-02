<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Cache\Mocks;

use Edoger\Cache\Drivers\ApcuDriver;

class DisabledApcuDriver extends ApcuDriver
{
    public static function isEnabled(): bool
    {
        return false;
    }
}
