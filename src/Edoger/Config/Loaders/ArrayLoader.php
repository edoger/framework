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
use Edoger\Config\Repository;
use Edoger\Config\AbstractLoader;

class ArrayLoader extends AbstractLoader
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
     * The array loader constructor.
     *
     * @param string $directory The directory of the configuration files.
     * @param string $suffix    The suffix of the configuration file name.
     *
     * @return void
     */
    public function __construct(string $directory, string $suffix = '.php')
    {
        $this->directory = rtrim(str_replace('\\', '/', $directory), '/').'/';
        $this->suffix    = $suffix;
    }

    /**
     * Load the configuration group.
     *
     * @param string  $group The configuration group name.
     * @param Closure $next  The trigger for the next loader.
     *
     * @return Edoger\Config\Repository
     */
    public function load(string $group, Closure $next): Repository
    {
        if (file_exists($file = $this->directory.$group.$this->suffix)) {
            return new Repository($this->read($file));
        }

        return $next();
    }

    /**
     * Read the configuration item in the configuration file.
     *
     * @param string $file The configuration file path.
     *
     * @return array
     */
    protected function read(string $file): array
    {
        $items = require $file;

        return is_array($items) ? $items : [];
    }
}
