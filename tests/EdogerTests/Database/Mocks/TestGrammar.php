<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Mocks;

use Edoger\Database\MySQL\Arguments;
use Edoger\Database\MySQL\Grammars\SQLStatement;
use Edoger\Database\MySQL\Grammars\AbstractGrammar;

class TestGrammar extends AbstractGrammar
{
    public function compile(Arguments $arguments = null): SQLStatement
    {
        return new SQLStatement($arguments);
    }
}
