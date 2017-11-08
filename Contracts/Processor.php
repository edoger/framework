<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow\Contracts;

use Closure;
use Edoger\Container\Container;

interface Processor
{
    /**
     * Process the current task.
     *
     * @param  Edoger\Container\Container $input The processor input parameter container.
     * @param  Closure                    $next  The trigger for the next processor.
     * @return mixed
     */
    public function process(Container $input, Closure $next);
}
