<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Util\Cases;

use Edoger\Util\Util;
use PHPUnit\Framework\TestCase;
use EdogerTests\Util\Mocks\TestWrapper;

class UtilTest extends TestCase
{
    public function testUtilValue()
    {
        $this->assertEquals('foo', Util::value('foo'));
        $this->assertEquals('foo', Util::value(new TestWrapper('foo')));
        $this->assertEquals('foo', Util::value(function () {
            return 'foo';
        }));
    }
}
