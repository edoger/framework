<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Request;

use Edoger\Http\Foundation\Headers as FoundationHeaders;
use Edoger\Util\Arr;
use Edoger\Util\Str;

class Headers extends FoundationHeaders
{
    /**
     * The request headers constructor.
     *
     * @param  mixed  $server Server and execution environment variables.
     * @return void
     */
    public function __construct($server = [])
    {
        $headers        = [];
        $contentHeaders = [
            'CONTENT_LENGTH' => 'content-length',
            'CONTENT_MD5'    => 'content-md5',
            'CONTENT_TYPE'   => 'content-type',
        ];

        // Get the request headers from the server and execution environment variables.
        foreach (Arr::convert($server) as $key => $value) {
            if (0 === Str::strpos($key, 'HTTP_')) {
                $headers[Str::substr($key, 5)] = $value;
            } elseif (isset($contentHeaders[$key])) {
                $headers[$contentHeaders[$key]] = $value;
            }
        }

        parent::__construct($headers);
    }

    /**
     * Create request headers collection.
     *
     * @param  iterable $server Server and execution environment variables.
     * @return self
     */
    public static function create(iterable $server): self
    {
        return new static($server);
    }
}
