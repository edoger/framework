<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Flow\Mocks;

use Edoger\Flow\Contracts\Processor;
use Edoger\Flow\Traits\EmptyProcessorSupport;

class TestEmptyProcessor implements Processor
{
    use EmptyProcessorSupport;
}
