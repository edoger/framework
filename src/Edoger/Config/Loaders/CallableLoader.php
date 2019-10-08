<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config\Loaders;

use Closure;
use RuntimeException;
use Edoger\Config\Repository;
use Edoger\Config\AbstractLoader;

class CallableLoader extends AbstractLoader
{
    /**
     * The configuration group callable loader.
     *
     * @var callable
     */
    protected $loader;

    /**
     * The callable loader constructor.
     *
     * @param callable $loader The configuration group callable loader.
     *
     * @return void
     */
    public function __construct(callable $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Load the configuration group.
     *
     * @param string  $group  The configuration group name.
     * @param bool    $reload Whether to reload the configuration group.
     * @param Closure $next   The trigger for the next loader.
     *
     * @return Repository
     */
    public function load(string $group, bool $reload, Closure $next): Repository
    {
        $repository = call_user_func($this->loader, $group, $reload, $next);

        if ($repository instanceof Repository) {
            return $repository;
        }

        throw new RuntimeException(
            'The configuration group callable loader must return "Edoger\Config\Repository" instance.'
        );
    }
}
