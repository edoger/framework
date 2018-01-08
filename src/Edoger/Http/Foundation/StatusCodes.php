<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Foundation;

use InvalidArgumentException;

class StatusCodes
{
    /**
     * The Hypertext Transfer Protocol (HTTP) status codes.
     *
     * 1xx: Informational - Request received, continuing process.
     * 2xx: Success       - The action was successfully received, understood, and accepted.
     * 3xx: Redirection   - Further action must be taken in order to complete the request.
     * 4xx: Client Error  - The request contains bad syntax or cannot be fulfilled.
     * 5xx: Server Error  - The server failed to fulfill an apparently valid request.
     *
     * Last Updated: 2017-04-14
     *
     * @var array
     *
     * @see https://www.iana.org/assignments/http-status-codes/http-status-codes.txt
     */
    protected static $codes = [
        100 => 'Continue',                        // RFC7231
        101 => 'Switching Protocols',             // RFC7231
        102 => 'Processing',                      // RFC2518
        200 => 'OK',                              // RFC7231
        201 => 'Created',                         // RFC7231
        202 => 'Accepted',                        // RFC7231
        203 => 'Non-Authoritative Information',   // RFC7231
        204 => 'No Content',                      // RFC7231
        205 => 'Reset Content',                   // RFC7231
        206 => 'Partial Content',                 // RFC7233
        207 => 'Multi-Status',                    // RFC4918
        208 => 'Already Reported',                // RFC5842
        226 => 'IM Used',                         // RFC3229
        300 => 'Multiple Choices',                // RFC7231
        301 => 'Moved Permanently',               // RFC7231
        302 => 'Found',                           // RFC7231
        303 => 'See Other',                       // RFC7231
        304 => 'Not Modified',                    // RFC7232
        305 => 'Use Proxy',                       // RFC7231
        306 => '(Unused)',                        // RFC7231
        307 => 'Temporary Redirect',              // RFC7231
        308 => 'Permanent Redirect',              // RFC7538
        400 => 'Bad Request',                     // RFC7231
        401 => 'Unauthorized',                    // RFC7235
        402 => 'Payment Required',                // RFC7231
        403 => 'Forbidden',                       // RFC7231
        404 => 'Not Found',                       // RFC7231
        405 => 'Method Not Allowed',              // RFC7231
        406 => 'Not Acceptable',                  // RFC7231
        407 => 'Proxy Authentication Required',   // RFC7235
        408 => 'Request Timeout',                 // RFC7231
        409 => 'Conflict',                        // RFC7231
        410 => 'Gone',                            // RFC7231
        411 => 'Length Required',                 // RFC7231
        412 => 'Precondition Failed',             // RFC7232 RFC8144
        413 => 'Payload Too Large',               // RFC7231
        414 => 'URI Too Long',                    // RFC7231
        415 => 'Unsupported Media Type',          // RFC7231
        416 => 'Range Not Satisfiable',           // RFC7233
        417 => 'Expectation Failed',              // RFC7231
        421 => 'Misdirected Request',             // RFC7540
        422 => 'Unprocessable Entity',            // RFC4918
        423 => 'Locked',                          // RFC4918
        424 => 'Failed Dependency',               // RFC4918
        426 => 'Upgrade Required',                // RFC7231
        428 => 'Precondition Required',           // RFC6585
        429 => 'Too Many Requests',               // RFC6585
        431 => 'Request Header Fields Too Large', // RFC6585
        451 => 'Unavailable For Legal Reasons',   // RFC7725
        500 => 'Internal Server Error',           // RFC7231
        501 => 'Not Implemented',                 // RFC7231
        502 => 'Bad Gateway',                     // RFC7231
        503 => 'Service Unavailable',             // RFC7231
        504 => 'Gateway Timeout',                 // RFC7231
        505 => 'HTTP Version Not Supported',      // RFC7231
        506 => 'Variant Also Negotiates',         // RFC2295
        507 => 'Insufficient Storage',            // RFC4918
        508 => 'Loop Detected',                   // RFC5842
        510 => 'Not Extended',                    // RFC2774
        511 => 'Network Authentication Required', // RFC6585
    ];

    /**
     * Custom HTTP status codes.
     *
     * @var array
     */
    protected static $custom = [];

    /**
     * Determines whether the given status code is valid.
     *
     * @param int $code The given status code.
     *
     * @return bool
     */
    public static function isValid(int $code): bool
    {
        return isset(static::$codes[$code]) || isset(static::$custom[$code]);
    }

    /**
     * Gets the description text for a given HTTP status code.
     *
     * @param int $code The given status code.
     *
     * @throws InvalidArgumentException Thrown when the HTTP status code is invalid.
     *
     * @return string
     */
    public static function getText(int $code): string
    {
        if (isset(static::$codes[$code])) {
            return static::$codes[$code];
        }

        if (isset(static::$custom[$code])) {
            return static::$custom[$code];
        }

        throw new InvalidArgumentException('Invalid HTTP status code.');
    }

    /**
     * Add a custom HTTP status code.
     *
     * @param int    $code The given status code.
     * @param string $text The given status text.
     *
     * @return bool
     */
    public static function addCustomStatusCode(int $code, string $text): bool
    {
        // Do not allow changes to existing status codes.
        if (static::isValid($code)) {
            return false;
        }

        static::$custom[$code] = $text;

        return true;
    }

    /**
     * Clear all custom HTTP status codes.
     *
     * @return void
     */
    public static function clearCustomStatusCodes(): void
    {
        static::$custom = [];
    }
}
