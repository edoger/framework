<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow\Tests\Support;

use Closure;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Processor;

class TestProcessor implements Processor
{
    protected $name;
    protected $value;

    public function __construct($name = 'processor', $value = 'Processor')
    {
        $this->name  = $name;
        $this->value = $value;
    }

    public function process(Container $input, Closure $next)
    {
        if ($input->get('name') === $this->name) {
            return $this->value;
        }

        return $next();
    }
}
