<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Http\Cases\Server\Request;

use PHPUnit\Framework\TestCase;
use Edoger\Http\Server\Request\Headers;
use Edoger\Http\Foundation\Headers as FoundationHeaders;

class HeadersTest extends TestCase
{
    public function testHeadersExtendsFoundationHeaders()
    {
        $headers = new Headers();

        $this->assertInstanceOf(FoundationHeaders::class, $headers);
    }

    public function testHeadersConstructor()
    {
        $headers = new Headers([
            'CONTENT_LENGTH' => 'CONTENT_LENGTH',
            'CONTENT_MD5'    => 'CONTENT_MD5',
            'CONTENT_TYPE'   => 'CONTENT_TYPE',
            'HTTP_X_HOST'    => 'HTTP_X_HOST',
            'X_TEST'         => 'X_TEST',
        ]);

        $this->assertEquals([
            'content-length' => 'CONTENT_LENGTH',
            'content-md5'    => 'CONTENT_MD5',
            'content-type'   => 'CONTENT_TYPE',
            'x-host'         => 'HTTP_X_HOST',
        ], $headers->toArray());
    }

    public function testHeadersCreate()
    {
        $headers = Headers::create([
            'CONTENT_LENGTH' => 'CONTENT_LENGTH',
            'CONTENT_MD5'    => 'CONTENT_MD5',
            'CONTENT_TYPE'   => 'CONTENT_TYPE',
            'HTTP_X_HOST'    => 'HTTP_X_HOST',
            'X_TEST'         => 'X_TEST',
        ]);

        $this->assertEquals([
            'content-length' => 'CONTENT_LENGTH',
            'content-md5'    => 'CONTENT_MD5',
            'content-type'   => 'CONTENT_TYPE',
            'x-host'         => 'HTTP_X_HOST',
        ], $headers->toArray());
    }
}
