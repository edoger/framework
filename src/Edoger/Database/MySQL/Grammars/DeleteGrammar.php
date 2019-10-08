<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Grammars;

use Edoger\Database\MySQL\Arguments;
use Edoger\Database\MySQL\Exceptions\GrammarException;
use Edoger\Database\MySQL\Grammars\Traits\LimitGrammarSupport;
use Edoger\Database\MySQL\Grammars\Traits\WhereGrammarSupport;
use Edoger\Database\MySQL\Grammars\Traits\WhereGrammarFoundationSupport;

class DeleteGrammar extends AbstractGrammar
{
    use WhereGrammarFoundationSupport, WhereGrammarSupport, LimitGrammarSupport;

    /**
     * Compile the current SQL statement.
     *
     * @throws GrammarException
     *
     * @return StatementContainer
     */
    public function compile(): StatementContainer
    {
        $arguments = Arguments::create();
        $fragments = Fragments::create(['DELETE FROM', $this->getWrappedFullTableName()]);

        if ($this->hasWhereFilter()) {
            $fragments->push('WHERE '.$this->getWhereFilter()->compile($arguments));
        }

        if ($this->hasLimit()) {
            $fragments->push('LIMIT '.$this->getLimit());
        }

        $container = new StatementContainer([
            Statement::create($fragments->assemble(), $arguments),
        ]);

        return $container;
    }
}
