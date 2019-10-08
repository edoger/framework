<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Grammars;

use Edoger\Container\Wrapper;
use Edoger\Database\MySQL\Exceptions\GrammarException;
use Edoger\Database\MySQL\Grammars\Traits\WhereGrammarSupport;

class WhereFilterWrapper extends Wrapper
{
    use WhereGrammarSupport;

    /**
     * The where filter wrapper constructor.
     *
     * @param string $connector The default filter connector.
     *
     * @throws GrammarException
     *
     * @return void
     */
    public function __construct(string $connector)
    {
        parent::__construct(new Filter($connector));
    }

    /**
     * Create a where filter wrapper instance.
     *
     * @param string $connector The default filter connector.
     *
     * @throws GrammarException
     *
     * @return Wrapper
     */
    public function createWhereFilterWrapper(string $connector): Wrapper
    {
        return new static($connector);
    }

    /**
     * Get the where filter instance.
     *
     * @return Filter
     */
    public function getWhereFilter(): Filter
    {
        return $this->getOriginal();
    }
}
