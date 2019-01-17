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
use Edoger\Event\Contracts\Trigger as TriggerContract;

class Trigger extends DispatcherContainer implements TriggerContract
{
    use TriggerSupport;
}
