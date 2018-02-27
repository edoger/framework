<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config\Loaders;

use Edoger\Util\Arr;
use Edoger\Util\Validator;
use Edoger\Config\AbstractFileLoader;

class XmlLoader extends AbstractFileLoader
{
    /**
     * The xml loader constructor.
     *
     * @param string $directory The directory of the configuration files.
     * @param string $suffix    The suffix of the configuration file name.
     *
     * @return void
     */
    public function __construct(string $directory, string $suffix = '.xml')
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
        if (Validator::isNotEmptyString($xml = file_get_contents($file))) {
            return Arr::convertFromXml($xml);
        }

        return [];
    }
}
