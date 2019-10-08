<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow\Traits;

use Closure;
use Edoger\Container\Container;

trait EmptyProcessorSupport
{
    /**
     * Process the current task.
     *
     * @param Container $input The processor input parameter container.
     * @param Closure   $next  The trigger for the next processor.
     *
     * @return mixed
     */
    public function process(Container $input, Closure $next)
    {
        // This processor only triggers the next processor and does nothing.
        // This is done solely to implement the processor interface.
        return $next();
    }
}
