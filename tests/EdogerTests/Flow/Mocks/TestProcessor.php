<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Flow\Mocks;

use Closure;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Processor;

class TestProcessor implements Processor
{
    protected $map;

    public function __construct(array $map = [])
    {
        $this->map = $map;
    }

    public function process(Container $input, Closure $next)
    {
        if ($input->has('key')) {
            $key = $input->get('key');

            if (isset($this->map[$key])) {
                return $this->map[$key];
            }
        }

        return $next();
    }
}
