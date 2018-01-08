<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config;

use Closure;
use InvalidArgumentException;
use Edoger\Container\Container;
use Edoger\Config\Contracts\Loader;
use Edoger\Flow\Contracts\Processor;

abstract class AbstractLoader implements Processor
{
    /**
     * Process configuration group load task.
     *
     * @param Edoger\Containers\Container $input The processor input parameters.
     * @param Closure                     $next  The trigger for the next processor.
     *
     * @throws InvalidArgumentException Throws when the configuration group name is invalid.
     *
     * @return mixed
     */
    final public function process(Container $input, Closure $next)
    {
        $group = $input->get('group');

        // The configuration group name must be a non-empty string.
        // This exception will be automatically captured, and you will get it in the blocker.
        if ('' === $group) {
            throw new InvalidArgumentException('Invalid configuration group name.');
        }

        return $this->load($group, $next);
    }

    /**
     * Load the configuration group.
     *
     * @param string  $group The configuration group name.
     * @param Closure $next  The trigger for the next loader.
     *
     * @return Edoger\Config\Repository
     */
    abstract public function load(string $group, Closure $next): Repository;
}
