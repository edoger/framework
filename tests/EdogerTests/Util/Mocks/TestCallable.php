<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Util\Mocks;

class TestCallable
{
    public function method1(&$value, &$key, $parameter)
    {
        $key   = 'test1';
        $value = 'test1';

        return true;
    }

    public static function method2(&$value, &$key, $parameter)
    {
        $key   = 'test2';
        $value = 'test2';

        return true;
    }
}
