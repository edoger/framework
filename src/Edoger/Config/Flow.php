<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config;

use Closure;
use Edoger\Container\Container;
use Edoger\Flow\Flow as BaseFlow;
use Edoger\Flow\Contracts\Processor;

class Flow extends BaseFlow
{
    /**
     * Run the flow processor.
     *
     * @param Processor $processor The flow processor.
     * @param Container $input     The processor input parameter container.
     * @param Closure   $next      The trigger for the next processor.
     *
     * @return mixed
     */
    protected function doProcess(Processor $processor, Container $input, Closure $next)
    {
        return $processor->load($input->get('group'), $input->get('reload'), $next);
    }
}
