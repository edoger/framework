<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Response\Renderers;

use Edoger\Http\Foundation\Collection;
use Edoger\Http\Server\Contracts\ResponseRenderer;

class SimpleRenderer implements ResponseRenderer
{
    /**
     * The response body.
     *
     * @var string
     */
    protected $body;

    /**
     * The simple response renderer constructor.
     *
     * @param string $body The response body.
     *
     * @return void
     */
    public function __construct(string $body)
    {
        $this->body = $body;
    }

    /**
     * Create an empty renderer instance.
     *
     * @param string $body The response body.
     *
     * @return self
     */
    public static function create(string $body = ''): self
    {
        return new static($body);
    }

    /**
     * Render the HTTP response body.
     *
     * @param Collection $content The HTTP response content collection.
     *
     * @return string
     */
    public function render(Collection $content): string
    {
        return $this->body;
    }
}
