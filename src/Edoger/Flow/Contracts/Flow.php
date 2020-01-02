<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow\Contracts;

interface Flow
{
    /**
     * Start the current flow processor queue.
     *
     * @param mixed $input The processor parameters.
     *
     * @return mixed
     */
    public function start($input = []);
}
