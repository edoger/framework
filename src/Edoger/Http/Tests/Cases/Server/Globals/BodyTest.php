<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Tests\Cases\Server\Globals;

use Edoger\Http\Foundation\Collection;
use Edoger\Http\Server\Globals\Body;
use PHPUnit\Framework\TestCase;

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

    public function testBodyCreateFromGlobals()
    {
        if (empty($_POST)) {
            $_POST['test'] = 'test';
        }
        
        $body = Body::createFromGlobals();

        $this->assertInstanceOf(Collection::class, $body);
        $this->assertEquals($_POST, $body->toArray());
    }
}
