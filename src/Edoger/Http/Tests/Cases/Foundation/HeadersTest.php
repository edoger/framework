<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Tests\Cases\Foundation;

use Countable;
use IteratorAggregate;
use PHPUnit\Framework\TestCase;
use Edoger\Http\Foundation\Headers;
use Edoger\Util\Contracts\Arrayable;

class HeadersTest extends TestCase
{
    public function testHeadersInstanceOfArrayable()
    {
        $headers = new Headers();

        $this->assertInstanceOf(Arrayable::class, $headers);
    }

    public function testHeadersInstanceOfCountable()
    {
        $headers = new Headers();

        $this->assertInstanceOf(Countable::class, $headers);
    }

    public function testHeadersInstanceOfIteratorAggregate()
    {
        $headers = new Headers();

        $this->assertInstanceOf(IteratorAggregate::class, $headers);
    }

    public function testHeadersHas()
    {
        $headers = new Headers(['TEST_HEADER' => 'test']);

        $this->assertTrue($headers->has('TEST_HEADER'));
        $this->assertTrue($headers->has('Test-Header'));
        $this->assertTrue($headers->has('test-header'));
        $this->assertFalse($headers->has('NON_HEADER'));
        $this->assertFalse($headers->has('Non-Header'));
        $this->assertFalse($headers->has('non-header'));
    }

    public function testHeadersGet()
    {
        $headers = new Headers(['TEST_HEADER' => 'test']);

        $this->assertEquals('test', $headers->get('TEST_HEADER'));
        $this->assertEquals('test', $headers->get('Test-Header'));
        $this->assertEquals('test', $headers->get('test-header'));
        $this->assertEquals('', $headers->get('NON_HEADER'));
        $this->assertEquals('', $headers->get('Non-Header'));
        $this->assertEquals('', $headers->get('non-header'));
        $this->assertEquals('test', $headers->get('NON_HEADER', 'test'));
        $this->assertEquals('test', $headers->get('Non-Header', 'test'));
        $this->assertEquals('test', $headers->get('non-header', 'test'));
    }

    public function testHeadersArrayable()
    {
        $headers = new Headers();
        $this->assertEquals([], $headers->toArray());

        $headers = new Headers(['TEST_HEADER' => 'test']);
        $this->assertEquals(['test-header' => 'test'], $headers->toArray());
    }

    public function testHeadersCountable()
    {
        $this->assertEquals(0, count(new Headers()));
        $this->assertEquals(1, count(new Headers(['TEST_HEADER' => 'test'])));
    }

    public function testHeadersIteratorAggregate()
    {
        $arr     = ['test-header' => 'foo', 'test-x-header' => 'bar'];
        $headers = new Headers($arr);

        foreach ($headers as $key => $value) {
            $this->assertEquals($arr[$key], $value);
        }
    }
}
