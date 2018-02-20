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
use Edoger\Util\Arr;
use Edoger\Util\Environment;

abstract class AbstractFileLoader extends AbstractLoader
{
    /**
     * The directory of the configuration files.
     *
     * @var string
     */
    protected $directory;

    /**
     * The suffix of the configuration file name.
     *
     * @var string
     */
    protected $suffix;

    /**
     * The abstract file loader constructor.
     *
     * @param string $directory The directory of the configuration files.
     * @param string $suffix    The suffix of the configuration file name.
     *
     * @return void
     */
    public function __construct(string $directory, string $suffix)
    {
        if (Environment::isWindows()) {
            $directory = str_replace('\\', '/', $directory);
        }

        $this->directory = rtrim($directory, '/').'/';
        $this->suffix    = $suffix;
    }

    /**
     * Load the configuration group.
     *
     * @param string  $group  The configuration group name.
     * @param bool    $reload Whether to reload the configuration group.
     * @param Closure $next   The trigger for the next loader.
     *
     * @return Edoger\Config\Repository
     */
    public function load(string $group, bool $reload, Closure $next): Repository
    {
        // Try to load only if the configuration file exists, or else we pass the
        // load to the next loader.
        if (file_exists($file = $this->directory.$group.$this->suffix)) {
            return new Repository(Arr::wrap($this->read($file), $group));
        }

        return $next();
    }

    /**
     * Read the configuration item from the configuration file.
     *
     * @param string $file The configuration file path.
     *
     * @return mixed
     */
    abstract protected function read(string $file);
}
