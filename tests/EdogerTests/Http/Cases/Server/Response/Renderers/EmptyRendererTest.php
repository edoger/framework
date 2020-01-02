<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Http\Cases\Server\Response\Renderers;

use PHPUnit\Framework\TestCase;
use Edoger\Http\Foundation\Collection;
use Edoger\Http\Server\Contracts\ResponseRenderer;
use Edoger\Http\Server\Response\Renderers\EmptyRenderer;

class EmptyRendererTest extends TestCase
{
    public function testEmptyRendererInstanceOfResponseRenderer()
    {
        $renderer = new EmptyRenderer();

        $this->assertInstanceOf(ResponseRenderer::class, $renderer);
    }

    public function testEmptyRendererCreate()
    {
        $renderer = EmptyRenderer::create();

        $this->assertInstanceOf(ResponseRenderer::class, $renderer);
    }

    public function testEmptyRendererRender()
    {
        $renderer   = EmptyRenderer::create();
        $collection = new Collection();

        $this->assertEquals('', $renderer->render($collection));
    }
}
