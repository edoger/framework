<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Container;

use Edoger\Util\Contracts\Wrapper as WrapperContract;

class Wrapper implements WrapperContract
{
    /**
     * The original data.
     *
     * @var mixed
     */
    protected $original;

    /**
     * The wrapper constructor.
     *
     * @param mixed $original The original data.
     *
     * @return void
     */
    public function __construct($original)
    {
        $this->original = $original;
    }

    /**
     * Gets the current original data.
     *
     * @return mixed
     */
    public function getOriginal()
    {
        return $this->original;
    }
}
