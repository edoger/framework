<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Grammars;

use Edoger\Container\Wrapper;
use Edoger\Database\MySQL\Exceptions\GrammarException;
use Edoger\Database\MySQL\Grammars\Traits\HavingGrammarSupport;

class HavingFilterWrapper extends Wrapper
{
    use HavingGrammarSupport;

    /**
     * The having filter wrapper constructor.
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
     * Create a having filter wrapper instance.
     *
     * @param string $connector The default filter connector.
     *
     * @throws GrammarException
     *
     * @return Wrapper
     */
    public function createHavingFilterWrapper(string $connector): Wrapper
    {
        return new static($connector);
    }

    /**
     * Get the having filter instance.
     *
     * @return Filter
     */
    public function getHavingFilter(): Filter
    {
        return $this->getOriginal();
    }
}
