<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config\Loaders;

use Closure;
use Edoger\Config\Repository;
use Edoger\Config\AbstractLoader;

class JsonLoader extends AbstractLoader
{
    /**
     * The directory of the json files.
     *
     * @var string
     */
    protected $directory;

    /**
     * The suffix of the json file name.
     *
     * @var string
     */
    protected $suffix;

    /**
     * The json loader constructor.
     *
     * @param string $directory The directory of the json files.
     * @param string $suffix    The suffix of the json file name.
     *
     * @return void
     */
    public function __construct(string $directory, string $suffix = '.json')
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
     * Read the configuration item in the json file.
     *
     * @param string $file The json file path.
     *
     * @return array
     */
    protected function read(string $file): array
    {
        $json  = file_get_contents($file);
        $items = $json ? json_decode($json, true) : [];

        return is_array($items) ? $items : [];
    }
}
