<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL\Grammars\Traits;

use Edoger\Container\Wrapper;
use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Grammars\Filter;
use Edoger\Database\MySQL\Grammars\WhereFilterWrapper;
use EdogerTests\Database\Mocks\TestWhereGrammarFoundationSupport;

class WhereGrammarFoundationSupportTest extends TestCase
{
    protected function createTestWhereGrammarFoundationSupport()
    {
        return new TestWhereGrammarFoundationSupport();
    }

    public function testWhereGrammarFoundationSupportHasWhereFilter()
    {
        $support = $this->createTestWhereGrammarFoundationSupport();

        $this->assertFalse($support->hasWhereFilter());
        $support->getWhereFilter();
        $this->assertFalse($support->hasWhereFilter());
        $support->getWhereFilter()->addColumnFilter('foo', 'foo');
        $this->assertTrue($support->hasWhereFilter());
    }

    public function testWhereGrammarFoundationSupportCreateWhereFilterWrapper()
    {
        $support = $this->createTestWhereGrammarFoundationSupport();

        $this->assertInstanceOf(Wrapper::class, $support->createWhereFilterWrapper('and'));
        $this->assertInstanceOf(WhereFilterWrapper::class, $support->createWhereFilterWrapper('and'));
    }

    public function testWhereGrammarFoundationSupportGetWhereFilter()
    {
        $support = $this->createTestWhereGrammarFoundationSupport();

        $this->assertInstanceOf(Filter::class, $support->getWhereFilter());
    }
}
