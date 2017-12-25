<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Tests\Support;

use Edoger\Http\Foundation\Collection;
use Edoger\Http\Server\Contracts\ResponseRenderer;

class TestResponseRenderer implements ResponseRenderer
{
    public function render(Collection $content): string
    {
        return print_r($content->toArray(), true);
    }
}
