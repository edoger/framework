<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL\Foundation;

use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Foundation\Operator;
use Edoger\Database\MySQL\Exceptions\GrammarException;

class OperatorTest extends TestCase
{
    public function testOperatorStandardizeSimpleOperator()
    {
        $operators = [
            '='         => '=',
            'eq'        => '=',
            '!='        => '!=',
            '<>'        => '!=',
            'neq'       => '!=',
            '<'         => '<',
            'lt'        => '<',
            '<='        => '<=',
            'lte'       => '<=',
            '>'         => '>',
            'gt'        => '>',
            '>='        => '>=',
            'gte'       => '>=',
            '?='        => 'LIKE',
            'like'      => 'LIKE',
            '!?='       => 'NOT LIKE',
            'notlike'   => 'NOT LIKE',
            '~='        => 'REGEXP',
            'regexp'    => 'REGEXP',
            '!~='       => 'NOT REGEXP',
            'notregexp' => 'NOT REGEXP',
        ];

        foreach ($operators as $key => $value) {
            $this->assertEquals($value, Operator::standardizeSimpleOperator($key));
        }

        $this->assertEquals('=', Operator::standardizeSimpleOperator(true));
        $this->assertEquals('!=', Operator::standardizeSimpleOperator(false));
    }

    public function testOperatorStandardizeSimpleOperatorFail()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('The given filter simple operator is invalid.');

        Operator::standardizeSimpleOperator('foo'); // exception
    }

    public function testOperatorStandardizeRangeOperator()
    {
        $this->assertEquals('IN', Operator::standardizeRangeOperator('in'));
        $this->assertEquals('NOT IN', Operator::standardizeRangeOperator('notin'));
        $this->assertEquals('IN', Operator::standardizeRangeOperator(true));
        $this->assertEquals('NOT IN', Operator::standardizeRangeOperator(false));
    }

    public function testOperatorStandardizeRangeOperatorFail()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('The given filter range operator is invalid.');

        Operator::standardizeRangeOperator('foo'); // exception
    }

    public function testOperatorStandardizeNullOperator()
    {
        $this->assertEquals('IS NULL', Operator::standardizeNullOperator(true));
        $this->assertEquals('IS NOT NULL', Operator::standardizeNullOperator(false));
    }

    public function testOperatorStandardizeNullOperatorFail()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('The given filter null operator is invalid.');

        Operator::standardizeNullOperator('foo'); // exception
    }
}
