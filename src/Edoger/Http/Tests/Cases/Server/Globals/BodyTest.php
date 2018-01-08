<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Tests\Cases\Server\Globals;

use PHPUnit\Framework\TestCase;
use Edoger\Http\Server\Globals\Body;
use Edoger\Http\Foundation\Collection;

class BodyTest extends TestCase
{
    public function testBodyExtendsCollection()
    {
        $body = new Body();

        $this->assertInstanceOf(Collection::class, $body);
    }

    public function testBodyCreate()
    {
        $body = Body::create(['test' => 'test']);

        $this->assertInstanceOf(Collection::class, $body);
        $this->assertEquals(['test' => 'test'], $body->toArray());
    }
}
