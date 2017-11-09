<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config\Loaders;

use Closure;
use Edoger\Config\AbstractLoader;
use Edoger\Config\Repository;
use RuntimeException;

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
     * @param  callable $loader The configuration group callable loader.
     * @return void
     */
    public function __construct(callable $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Load the configuration group.
     *
     * @param  string                     $group The configuration group name.
     * @param  Closure                    $next  The trigger for the next loader.
     * @return Edoger\Config\Repository
     */
    public function load(string $group, Closure $next): Repository
    {
        $repository = call_user_func($this->loader, $group, $next);

        if ($repository instanceof Repository) {
            return $repository;
        }

        throw new RuntimeException(
            'The configuration group callable loader must return a repository instance.'
        );
    }
}
