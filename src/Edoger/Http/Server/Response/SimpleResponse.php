<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Response;

use Edoger\Http\Server\Response\Renderers\SimpleRenderer;

class SimpleResponse extends Response
{
    /**
     * The simple response constructor.
     *
     * @param int      $status  The HTTP response status code.
     * @param string   $body    The response body.
     * @param iterable $headers The response headers.
     *
     * @return void
     */
    public function __construct(int $status, string $body, iterable $headers = [])
    {
        parent::__construct($status, [], $headers);

        // Response body content is given by the constructor.
        // Response data collection will no longer affect the response body.
        $this->setResponseRenderer(SimpleRenderer::create($body));
    }
}
