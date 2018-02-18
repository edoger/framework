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
use Edoger\Util\Arr;
use Edoger\Config\Repository;
use Edoger\Config\AbstractLoader;
use Edoger\Serializer\JsonSerializer;

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
     * The JSON serializer.
     *
     * @var Edoger\Serializer\JsonSerializer
     */
    protected $serializer;

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
        $this->directory  = rtrim(str_replace('\\', '/', $directory), '/').'/';
        $this->suffix     = $suffix;
        $this->serializer = JsonSerializer::create();
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
        $file = $this->directory.$group.$this->suffix;

        if (file_exists($file)) {
            return new Repository($this->read($file, $group));
        }

        return $next();
    }

    /**
     * Read the configuration item in the json file.
     *
     * @param string $file  The json file path.
     * @param string $group The configuration group name.
     *
     * @return array
     */
    protected function read(string $file, string $group): array
    {
        $json  = file_get_contents($file);
        $items = $json ? $this->serializer->deserialize($json, true) : [];

        return Arr::wrap($items, $group);
    }
}
