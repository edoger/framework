<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Http\Cases\Foundation;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Edoger\Http\Foundation\StatusCodes;

class StatusCodesTest extends TestCase
{
    protected $codes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];

    public function testStatusCodesIsValid()
    {
        foreach ($this->codes as $code => $text) {
            $this->assertTrue(StatusCodes::isValid($code));
        }

        $this->assertFalse(StatusCodes::isValid(1000));
        $this->assertFalse(StatusCodes::isValid(1000));
        $this->assertFalse(StatusCodes::isValid(-1000));
    }

    public function testStatusCodesGetText()
    {
        foreach ($this->codes as $code => $text) {
            $this->assertEquals($text, StatusCodes::getText($code));
        }
    }

    public function testStatusCodesGetTextFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid HTTP status code.');

        StatusCodes::getText(1000); // exception
    }

    public function testStatusCodesAddCustomStatusCode()
    {
        $this->assertFalse(StatusCodes::addCustomStatusCode(200, 'Test Status Code'));
        $this->assertTrue(StatusCodes::addCustomStatusCode(900, 'Test Status Code'));

        $this->assertTrue(StatusCodes::isValid(200));
        $this->assertTrue(StatusCodes::isValid(900));

        $this->assertEquals('OK', StatusCodes::getText(200));
        $this->assertEquals('Test Status Code', StatusCodes::getText(900));
    }

    public function testStatusCodesClearCustomStatusCodes()
    {
        StatusCodes::clearCustomStatusCodes(); // init

        StatusCodes::addCustomStatusCode(900, 'Test Status Code');

        $this->assertTrue(StatusCodes::isValid(900));
        StatusCodes::clearCustomStatusCodes();
        $this->assertFalse(StatusCodes::isValid(900));
    }
}
