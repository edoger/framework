<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config;

use Edoger\Config\Loaders\CallableLoader;
use Edoger\Event\Collector;
use Edoger\Event\Dispatcher;
use Edoger\Event\Factory;
use Edoger\Event\Trigger;
use Edoger\Flow\Flow;
use Edoger\Util\Arr;
use InvalidArgumentException;
use RuntimeException;

class Config extends Collector
{
    /**
     * The event trigger.
     *
     * @var Edoger\Event\Trigger
     */
    protected $trigger;

    /**
     * The configuration group load flow.
     *
     * @var Edoger\Flow\Flow
     */
    protected $flow;

    /**
     * All loaded configuration groups.
     *
     * @var array
     */
    protected $groups = [];

    /**
     * The config constructor.
     *
     * @param  iterable                 $loaders    The configuration group loaders.
     * @param  Edoger\Event\Dispatcher  $dispatcher The configuration event dispatcher.
     * @throws InvalidArgumentException Thrown when the configuration group loader is invalid.
     * @return void
     */
    public function __construct(iterable $loaders = [], Dispatcher $dispatcher = null)
    {
        if (is_null($dispatcher)) {
            $dispatcher = Factory::createEdogerDispatcher('config');
        }

        parent::__construct($dispatcher);

        $this->trigger = new Trigger($dispatcher);
        $this->flow    = new Flow(new Blocker($this->getTrigger()));

        foreach ($loaders as $loader) {
            $this->pushLoader($loader);
        }
    }

    /**
     * Get the configuration event trigger.
     *
     * @return Edoger\Event\Trigger
     */
    protected function getTrigger(): Trigger
    {
        return $this->trigger;
    }

    /**
     * Get the configuration group load flow.
     *
     * @return Edoger\Flow\Flow
     */
    protected function getFlow(): Flow
    {
        return $this->flow;
    }

    /**
     * Determines whether the current loader collection is empty.
     *
     * @return boolean
     */
    public function isEmptyLoaders(): bool
    {
        return $this->getFlow()->isEmpty();
    }

    /**
     * Get the size of the current group configuration loader collection.
     *
     * @return integer
     */
    public function countLoaders(): int
    {
        return $this->getFlow()->count();
    }

    /**
     * Gets the current configuration group loaders.
     *
     * @return array
     */
    public function getLoaders(): array
    {
        // The loader is stored in the form of a stack,
        // and we have to restore the order of their additions.
        return array_reverse($this->getFlow()->toArray());
    }

    /**
     * Append a configuration group loader.
     *
     * @param  Edoger\Config\AbstractLoader|callable $loader The configuration group loader.
     * @return integer
     */
    public function pushLoader($loader): int
    {
        if ($loader instanceof AbstractLoader) {
            return $this->getFlow()->append($loader, true);
        } elseif (is_callable($loader)) {
            return $this->getFlow()->append(new CallableLoader($loader), true);
        } else {
            throw new InvalidArgumentException('Invalid configuration group loader.');
        }
    }

    /**
     * Delete and return a configuration group loader.
     *
     * @throws RuntimeException               Throws when the configuration group loader collection is empty.
     * @return Edoger\Config\AbstractLoader
     */
    public function popLoader(): AbstractLoader
    {
        if ($this->isEmptyLoaders()) {
            throw new RuntimeException(
                'Unable to remove loader from the empty loader stack.'
            );
        }

        return $this->getFlow()->remove(true);
    }

    /**
     * Clear the current configuration group loader collection.
     *
     * @return self
     */
    public function clearLoaders()
    {
        $this->getFlow()->clear();

        return $this;
    }

    /**
     * Gets the configuration group collection for the given name.
     * If the configuration group does not exist, an empty collection is returned.
     *
     * @param  string                     $group  The configuration group name.
     * @param  boolean                    $reload Whether to reload the configuration group.
     * @return Edoger\Config\Repository
     */
    public function group(string $group, bool $reload = false): Repository
    {
        if ($reload || !Arr::has($this->groups, $group)) {
            $trigger = $this->getTrigger();

            // Trigger the "config.loading" event.
            if ($trigger->hasEventListener('loading')) {
                $trigger->emit('loading', [
                    'group'  => $group,
                    'reload' => $reload,
                ]);
            }

            $repository = $this->getFlow()->start([
                'group'  => $group,
                'reload' => $reload,
            ]);

            // Trigger the "config.loaded" event.
            if ($trigger->hasEventListener('loaded')) {
                $trigger->emit('loaded', [
                    'group'      => $group,
                    'reload'     => $reload,
                    'repository' => $repository,
                ]);
            }

            $this->groups[$group] = $repository;
        }

        return $this->groups[$group];
    }
}
