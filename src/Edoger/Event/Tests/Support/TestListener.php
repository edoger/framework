<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event\Tests\Support;

use Edoger\Event\Contracts\Listener;
use Edoger\Event\Dispatcher;
use Edoger\Event\Event;

class TestListener implements Listener
{
    protected $message;

    public function __construct(string $message = '')
    {
        $this->message = $message;
    }

    public function handle(Event $event, Dispatcher $dispatcher): void
    {
        echo $this->message;
    }
}
