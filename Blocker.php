<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config;

use Edoger\Container\Container;
use Edoger\Container\Wrapper;
use Edoger\Event\Trigger;
use Edoger\Flow\Contracts\Blocker as BlockerContract;
use Throwable;

class Blocker extends Wrapper implements BlockerContract
{
    /**
     * The configuration group load flow blocker constructor.
     *
     * @param  Edoger\Event\Trigger $trigger [description]
     * @return void
     */
    public function __construct(Trigger $trigger)
    {
        parent::__construct($trigger);
    }

    /**
     * Block the current call stack.
     *
     * @param  Edoger\Container\Container $input     The processor input parameter container.
     * @param  Throwable|null             $exception The captured processor exception.
     * @return Edoger\Config\Repository
     */
    public function block(Container $input, Throwable $exception = null)
    {
        $trigger = $this->getOriginal();

        if (is_null($exception)) {
            // Trigger the "config.missed" event.
            if ($trigger->hasEventListener('missed')) {
                $trigger->emit('missed', $input);
            }
        } else {
            // Trigger the "config.error" event.
            if ($trigger->hasEventListener('error')) {
                $trigger->emit('error', array_merge($input->toArray(), [
                    'exception' => $exception,
                ]));
            }
        }

        // By default, a repository is always returned.
        return new Repository();
    }
}
