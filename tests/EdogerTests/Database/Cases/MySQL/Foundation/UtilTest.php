<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL\Foundation;

use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Foundation\Util;

class UtilTest extends TestCase
{
    public function testUtilWrap()
    {
        $this->assertEquals('`foo`', Util::wrap('foo'));
    }
}
