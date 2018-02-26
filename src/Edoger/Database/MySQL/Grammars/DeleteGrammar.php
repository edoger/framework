<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Grammars;

use Edoger\Database\MySQL\Arguments;
use Edoger\Database\MySQL\Grammars\Traits\LimitGrammarSupport;
use Edoger\Database\MySQL\Grammars\Traits\WhereGrammarSupport;
use Edoger\Database\MySQL\Grammars\Traits\WhereGrammarFoundationSupport;

class DeleteGrammar extends AbstractGrammar
{
    use WhereGrammarFoundationSupport, WhereGrammarSupport, LimitGrammarSupport;

    /**
     * Compile the current instance to a statement string.
     *
     * @return Edoger\Database\MySQL\Grammars\StatementContainer
     */
    public function compile(): StatementContainer
    {
        $arguments = Arguments::create();
        $fragments = ['DELETE FROM', $this->getWrappedFullTableName()];

        if ($this->hasWhereFilter()) {
            $fragments[] = 'WHERE '.$this->getWhereFilter()->compile($arguments);
        }

        if ($this->hasLimit()) {
            $fragments[] = 'LIMIT '.$this->getLimit();
        }

        return new StatementContainer([
            Statement::create(implode(' ', $fragments), $arguments)
        ]);
    }
}
