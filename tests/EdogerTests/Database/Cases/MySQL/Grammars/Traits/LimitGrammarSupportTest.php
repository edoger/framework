<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL\Grammars\Traits;

use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Exceptions\GrammarException;
use EdogerTests\Database\Mocks\TestLimitGrammarSupport;

class LimitGrammarSupportTest extends TestCase
{
    protected function createTestLimitGrammarSupport()
    {
        return new TestLimitGrammarSupport();
    }

    public function testLimitGrammarSupportLimit()
    {
        $support = $this->createTestLimitGrammarSupport();

        $this->assertEquals($support, $support->limit(1));
        $this->assertEquals($support, $support->limit(0));
    }

    public function testLimitGrammarSupportLimitFail()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('The limit value can not be less than 0.');

        $this->createTestLimitGrammarSupport()->limit(-1); // exception
    }

    public function testLimitGrammarSupportHasLimit()
    {
        $support = $this->createTestLimitGrammarSupport();

        $this->assertFalse($support->hasLimit());
        $support->limit(1);
        $this->assertTrue($support->hasLimit());
        $support->limit(0);
        $this->assertFalse($support->hasLimit());
    }

    public function testLimitGrammarSupportGetLimit()
    {
        $support = $this->createTestLimitGrammarSupport();

        $this->assertEquals(0, $support->getLimit());
        $support->limit(1);
        $this->assertEquals(1, $support->getLimit());
    }
}
