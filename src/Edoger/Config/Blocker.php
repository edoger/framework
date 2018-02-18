<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config;

use Throwable;
use Edoger\Event\Trigger;
use Edoger\Container\Wrapper;
use Edoger\Container\Container;
use Edoger\Flow\Contracts\Blocker as BlockerContract;

class Blocker extends Wrapper implements BlockerContract
{
    /**
     * The configuration group load flow blocker constructor.
     *
     * @param Edoger\Event\Trigger $trigger [description]
     *
     * @return void
     */
    public function __construct(Trigger $trigger)
    {
        parent::__construct($trigger);
    }

    /**
     * Handle the flow block event.
     *
     * @param Edoger\Container\Container $input  The processor input parameter container.
     * @param Edoger\Config\Repository   $result The processor flow return value.
     *
     * @return Edoger\Config\Repository
     */
    public function block(Container $input, $result)
    {
        // A Edoger\Config\Repository instance.
        return $result;
    }

    /**
     * Handle the flow complete event.
     *
     * @param Edoger\Container\Container $input The processor input parameter container.
     *
     * @return Edoger\Config\Repository
     */
    public function complete(Container $input)
    {
        $trigger = $this->getOriginal();

        // Trigger the "config.missed" event.
        if ($trigger->hasEventListener('missed')) {
            $trigger->emit('missed', $input);
        }

        return new Repository();
    }

    /**
     * Handle the flow error event.
     *
     * @param Edoger\Container\Container $input     The processor input parameter container.
     * @param Throwable                  $exception The captured flow processor exception.
     *
     * @return Edoger\Config\Repository
     */
    public function error(Container $input, Throwable $exception)
    {
        $trigger = $this->getOriginal();

        // Trigger the "config.error" event.
        if ($trigger->hasEventListener('error')) {
            $trigger->emit('error', array_merge($input->toArray(), [
                'exception' => $exception,
            ]));
        }

        return new Repository();
    }
}
