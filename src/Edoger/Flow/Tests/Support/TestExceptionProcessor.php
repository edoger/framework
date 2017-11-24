<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow\Tests\Support;

use Closure;
use Exception;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Processor;

class TestExceptionProcessor implements Processor
{
    protected $message;

    public function __construct($message = 'ProcessorException')
    {
        $this->message = $message;
    }

    public function process(Container $input, Closure $next)
    {
        throw new Exception($this->message);
    }
}
