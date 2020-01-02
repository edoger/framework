<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Http\Cases\Server\Globals;

use PHPUnit\Framework\TestCase;
use Edoger\Http\Foundation\Collection;
use Edoger\Http\Server\Globals\Cookies;

class CookiesTest extends TestCase
{
    public function testCookiesExtendsCollection()
    {
        $cookies = new Cookies();

        $this->assertInstanceOf(Collection::class, $cookies);
    }

    public function testCookiesCreate()
    {
        $cookies = Cookies::create(['test' => 'test']);

        $this->assertInstanceOf(Collection::class, $cookies);
        $this->assertEquals(['test' => 'test'], $cookies->toArray());
    }
}
