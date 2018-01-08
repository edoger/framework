<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Response\Renderers;

use Edoger\Http\Foundation\Collection;
use Edoger\Http\Server\Contracts\ResponseRenderer;

class EmptyRenderer implements ResponseRenderer
{
    /**
     * The empty response renderer constructor.
     *
     * @return void
     */
    public function __construct()
    {
        // do nothing
    }

    /**
     * Create an empty renderer instance.
     *
     * @return self
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * Render the HTTP response body.
     *
     * @param Edoger\Http\Foundation\Collection $content The HTTP response content collection.
     *
     * @return string
     */
    public function render(Collection $content): string
    {
        // The empty renderer always returns an empty string.
        return '';
    }
}
