<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Contracts;

use Edoger\Http\Foundation\Collection;

interface ResponseRenderer
{
    /**
     * Render the HTTP response body.
     *
     * @param Collection $content The HTTP response content collection.
     *
     * @return string
     */
    public function render(Collection $content): string;
}
