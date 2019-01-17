<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow;

use Closure;
use Countable;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Processor;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Flow\Traits\ProcessorStoreSupport;

class Flow extends AbstractFlow implements Arrayable, Countable
{
    use ProcessorStoreSupport;

    /**
     * Run the flow processor.
     *
     * @param Edoger\Flow\Contracts\Processor $processor The flow processor.
     * @param Edoger\Container\Container      $input     The processor input parameter container.
     * @param Closure                         $next      The trigger for the next processor.
     *
     * @return mixed
     */
    protected function doProcess(Processor $processor, Container $input, Closure $next)
    {
        return $processor->process($input, $next);
    }

    /**
     * Returns the current flow processors as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->getStore()->toArray();
    }

    /**
     * Gets the size of the current flow processor store.
     *
     * @return int
     */
    public function count()
    {
        return $this->getStore()->count();
    }
}
