<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config;

use Edoger\Util\Arr;
use RuntimeException;
use Edoger\Event\Factory;
use Edoger\Event\Trigger;
use Edoger\Event\Collector;
use Edoger\Event\Dispatcher;
use InvalidArgumentException;
use Edoger\Config\Loaders\CallableLoader;

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
     * @var Edoger\Config\Flow
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
     * @param iterable                $loaders    The configuration group loaders.
     * @param Edoger\Event\Dispatcher $dispatcher The configuration event dispatcher.
     *
     * @throws InvalidArgumentException Thrown when the configuration group loader is invalid.
     *
     * @return void
     */
    public function __construct(iterable $loaders = [], Dispatcher $dispatcher = null)
    {
        // Initialize the configuration event collector.
        // If no event distributor is given, the system automatically creates a default event
        // distributor with an "edoger" top-level namespace.
        parent::__construct(
            is_null($dispatcher) ? Factory::createEdogerDispatcher() : $dispatcher,
            'config'
        );

        $this->initEventTrigger();
        $this->initLoadFlow();

        // Add predefined configuration group loaders.
        foreach ($loaders as $loader) {
            $this->pushLoader($loader);
        }
    }

    /**
     * Initialize the configuration event trigger.
     *
     * @return void
     */
    protected function initEventTrigger(): void
    {
        $this->trigger = new Trigger($this->getEventDispatcher(), 'config');
    }

    /**
     * Get the configuration event trigger.
     *
     * @return Edoger\Event\Trigger
     */
    protected function getEventTrigger(): Trigger
    {
        return $this->trigger;
    }

    /**
     * Initialize the configuration load flow.
     *
     * @return void
     */
    protected function initLoadFlow(): void
    {
        $this->flow = new Flow(
            new Blocker($this->getEventTrigger())
        );
    }

    /**
     * Get the configuration group load flow.
     *
     * @return Edoger\Config\Flow
     */
    protected function getLoadFlow(): Flow
    {
        return $this->flow;
    }

    /**
     * Trigger the "config.loading" event.
     *
     * @param array $input The event handler input parameters.
     *
     * @return void
     */
    protected function emitLoadingEvent(array $input): void
    {
        $trigger = $this->getEventTrigger();

        if ($trigger->hasEventListener('loading')) {
            $trigger->emit('loading', $input);
        }
    }

    /**
     * Trigger the "config.loaded" event.
     *
     * @param array                    $input      The event handler input parameters.
     * @param Edoger\Config\Repository $repository The configuration group repository instance.
     *
     * @return void
     */
    protected function emitLoadedEvent(array $input, Repository $repository): void
    {
        $trigger = $this->getEventTrigger();

        if ($trigger->hasEventListener('loaded')) {
            $trigger->emit('loaded', Arr::merge($input, ['repository' => $repository]));
        }
    }

    /**
     * Load a configuration group with a given name.
     *
     * @param string $group  The configuration group name.
     * @param bool   $reload Whether to reload the configuration group.
     *
     * @return Edoger\Config\Repository
     */
    protected function load(string $group, bool $reload): Repository
    {
        $input = ['group' => $group, 'reload' => $reload];

        $this->emitLoadingEvent($input);

        $repository = $this->getLoadFlow()->start($input);

        $this->emitLoadedEvent($input, $repository);

        return $repository;
    }

    /**
     * Determines whether the current loader collection is empty.
     *
     * @return bool
     */
    public function isEmptyLoaders(): bool
    {
        return $this->getLoadFlow()->isEmpty();
    }

    /**
     * Get the size of the current group configuration loader collection.
     *
     * @return int
     */
    public function countLoaders(): int
    {
        return $this->getLoadFlow()->count();
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
        return array_reverse($this->getLoadFlow()->toArray());
    }

    /**
     * Append a configuration group loader.
     *
     * @param Edoger\Config\AbstractLoader|callable $loader The configuration group loader.
     *
     * @return int
     */
    public function pushLoader($loader): int
    {
        if ($loader instanceof AbstractLoader) {
            return $this->getLoadFlow()->append($loader, true);
        } elseif (is_callable($loader)) {
            return $this->getLoadFlow()->append(new CallableLoader($loader), true);
        } else {
            throw new InvalidArgumentException('Invalid configuration group loader.');
        }
    }

    /**
     * Delete and return a configuration group loader.
     *
     * @throws RuntimeException Throws when the configuration group loader collection is empty.
     *
     * @return Edoger\Config\AbstractLoader
     */
    public function popLoader(): AbstractLoader
    {
        if ($this->isEmptyLoaders()) {
            throw new RuntimeException(
                'Unable to remove loader from the empty loader stack.'
            );
        }

        return $this->getLoadFlow()->remove(true);
    }

    /**
     * Clear the current configuration group loader collection.
     *
     * @return self
     */
    public function clearLoaders(): self
    {
        $this->getLoadFlow()->clear();

        return $this;
    }

    /**
     * Gets the configuration group collection for the given name.
     * If the configuration group does not exist, an empty collection is returned.
     *
     * @param string $group  The configuration group name.
     * @param bool   $reload Whether to reload the configuration group.
     *
     * @return Edoger\Config\Repository
     */
    public function group(string $group, bool $reload = false): Repository
    {
        if ($reload || !Arr::has($this->groups, $group)) {
            $this->groups[$group] = $this->load($group, $reload);
        }

        return $this->groups[$group];
    }
}
