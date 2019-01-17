<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Database\MySQL\Grammars\Traits;

use Edoger\Util\Validator;
use Edoger\Database\MySQL\Exceptions\GrammarException;

trait LimitGrammarSupport
{
    /**
     * The limit value.
     *
     * @var int
     */
    protected $limited = 0;

    /**
     * Set the limit value.
     *
     * @param int $limit The limit value.
     *
     * @throws Edoger\Database\MySQL\Exceptions\GrammarException Thrown when the limit is invalid.
     *
     * @return self
     */
    public function limit(int $limit)
    {
        if (Validator::isNegativeInteger($limit)) {
            throw new GrammarException('The limit value can not be less than 0.');
        }

        $this->limited = $limit;

        return $this;
    }

    /**
     * Determine whether there is a limit value.
     *
     * @return bool
     */
    public function hasLimit(): bool
    {
        return 0 < $this->limited;
    }

    /**
     * Get the limit value.
     *
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limited;
    }
}
