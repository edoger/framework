<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Tests\Cases\Server\Response;

use PHPUnit\Framework\TestCase;
use Edoger\Http\Server\Response\Headers;
use Edoger\Http\Foundation\Headers as FoundationHeaders;

class HeadersTest extends TestCase
{
    public function testHeadersExtendsFoundationHeaders()
    {
        $headers = new Headers();

        $this->assertInstanceOf(FoundationHeaders::class, $headers);
    }

    public function testHeadersSet()
    {
        $headers = new Headers();

        $headers->set('X-Host', 'test');
        $this->assertEquals(['x-host' => 'test'], $headers->toArray());
        $headers->set('X-Host', 'test.host');
        $this->assertEquals(['x-host' => 'test.host'], $headers->toArray());
        $headers->set('X-Name', 'test');
        $this->assertEquals(['x-host' => 'test.host', 'x-name' => 'test'], $headers->toArray());
    }

    public function testHeadersDelete()
    {
        $headers = new Headers([
            'X-Host' => 'test',
            'X-Name' => 'test',
        ]);

        $this->assertTrue($headers->has('X-Host'));
        $this->assertTrue($headers->has('X-Name'));

        $headers->delete('X-Host');
        $this->assertFalse($headers->has('X-Host'));
        $this->assertTrue($headers->has('X-Name'));

        $headers->delete('x-name');
        $this->assertFalse($headers->has('X-Host'));
        $this->assertFalse($headers->has('X-Name'));
        $this->assertEquals([], $headers->toArray());
    }

    public function testHeadersClear()
    {
        $headers = new Headers([
            'X-Host' => 'test',
            'X-Name' => 'test',
        ]);

        $this->assertTrue($headers->has('X-Host'));
        $this->assertTrue($headers->has('X-Name'));
        $this->assertEquals(['x-host' => 'test', 'x-name' => 'test'], $headers->toArray());
        $this->assertEquals($headers, $headers->clear());
        $this->assertFalse($headers->has('X-Host'));
        $this->assertFalse($headers->has('X-Name'));
        $this->assertEquals([], $headers->toArray());
    }
}
