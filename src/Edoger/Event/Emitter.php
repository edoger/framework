<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event;

use Edoger\Event\Traits\TriggerSupport;
use Edoger\Event\Traits\CollectorSupport;
use Edoger\Event\Contracts\Trigger as TriggerContract;
use Edoger\Event\Contracts\Collector as CollectorContract;

class Emitter extends DispatcherContainer implements CollectorContract, TriggerContract
{
    use CollectorSupport, TriggerSupport;
}
