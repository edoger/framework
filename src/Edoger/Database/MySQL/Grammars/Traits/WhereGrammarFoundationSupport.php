<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Grammars\Traits;

use Edoger\Container\Wrapper;
use Edoger\Database\MySQL\Grammars\Filter;
use Edoger\Database\MySQL\Grammars\WhereFilterWrapper;

trait WhereGrammarFoundationSupport
{
    /**
     * The where filter wrapper instance.
     *
     * @var WhereFilterWrapper|null
     */
    protected $whereFilterWrapper = null;

    /**
     * Determine if the where filter exists.
     *
     * @return bool
     */
    public function hasWhereFilter(): bool
    {
        if (is_null($this->whereFilterWrapper)) {
            return false;
        }

        return !$this->whereFilterWrapper->getOriginal()->isEmpty();
    }

    /**
     * Create a where filter wrapper instance.
     *
     * @param string $connector The default filter connector.
     *
     * @return Wrapper
     */
    public function createWhereFilterWrapper(string $connector): Wrapper
    {
        return new WhereFilterWrapper($connector);
    }

    /**
     * Get the where filter instance.
     *
     * @return Filter
     */
    public function getWhereFilter(): Filter
    {
        if (is_null($this->whereFilterWrapper)) {
            $this->whereFilterWrapper = $this->createWhereFilterWrapper('and');
        }

        return $this->whereFilterWrapper->getOriginal();
    }
}
