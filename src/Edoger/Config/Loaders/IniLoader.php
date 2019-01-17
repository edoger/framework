<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config\Loaders;

use Edoger\Config\AbstractFileLoader;

class IniLoader extends AbstractFileLoader
{
    /**
     * The ini loader constructor.
     *
     * @param string $directory The directory of the configuration files.
     * @param string $suffix    The suffix of the configuration file name.
     *
     * @return void
     */
    public function __construct(string $directory, string $suffix = '.ini')
    {
        parent::__construct($directory, $suffix);
    }

    /**
     * Read the configuration item from the configuration file.
     *
     * @param string $file The configuration file path.
     *
     * @return mixed
     */
    protected function read(string $file)
    {
        if (false !== $items = parse_ini_file($file, true)) {
            return $items;
        }

        return [];
    }
}
