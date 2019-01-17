<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow;

use Edoger\Flow\Contracts\Processor;
use Edoger\Flow\Traits\EmptyProcessorSupport;

class EmptyProcessor implements Processor
{
    use EmptyProcessorSupport;
}
