<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Http\Cases\Server\Response;

use PHPUnit\Framework\TestCase;
use Edoger\Http\Server\Response\Response;
use Edoger\Http\Server\Response\SimpleResponse;
use Edoger\Http\Server\Response\Renderers\SimpleRenderer;

class SimpleResponseTest extends TestCase
{
    public function testSimpleResponseExtendsResponse()
    {
        $response = new SimpleResponse(200, '');

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testSimpleResponseDefaultResponseRenderer()
    {
        $response = new SimpleResponse(200, '');

        $this->assertInstanceOf(SimpleRenderer::class, $response->getResponseRenderer());
    }

    public function testSimpleResponseSendBody()
    {
        $this->expectOutputString('foo');

        $response = new SimpleResponse(200, 'foo');

        $response->sendBody();
    }
}
