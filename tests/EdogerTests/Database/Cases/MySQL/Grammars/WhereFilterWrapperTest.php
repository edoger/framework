<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL\Grammars;

use stdClass;
use Edoger\Container\Wrapper;
use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Grammars\Filter;
use Edoger\Database\MySQL\Exceptions\GrammarException;
use Edoger\Database\MySQL\Grammars\WhereFilterWrapper;
use Edoger\Database\MySQL\Grammars\Traits\WhereGrammarSupport;

class WhereFilterWrapperTest extends TestCase
{
    protected function createWhereFilterWrapper(string $connector = 'and')
    {
        return new WhereFilterWrapper($connector);
    }

    public function testWhereFilterWrapperExtendsWrapper()
    {
        $filter = $this->createWhereFilterWrapper();

        $this->assertInstanceOf(Wrapper::class, $filter);
    }

    public function testWhereFilterWrapperUseTraitWhereGrammarSupport()
    {
        $uses     = class_uses($this->createWhereFilterWrapper());
        $abstract = WhereGrammarSupport::class;

        $this->assertArrayHasKey($abstract, $uses);
        $this->assertEquals($abstract, $uses[$abstract]);
    }

    public function testWhereFilterWrapperCreateWhereFilterWrapper()
    {
        $filter = $this->createWhereFilterWrapper();

        $this->assertInstanceOf(Wrapper::class, $filter->createWhereFilterWrapper('and'));
        $this->assertInstanceOf(WhereFilterWrapper::class, $filter->createWhereFilterWrapper('or'));
    }

    public function testWhereFilterWrapperGetWhereFilter()
    {
        $filter = $this->createWhereFilterWrapper();

        $this->assertInstanceOf(Filter::class, $filter->getWhereFilter());
        $this->assertEquals($filter->getOriginal(), $filter->getWhereFilter());
    }

    public function testWhereFilterWrapperWhere()
    {
        $filter = $this->createWhereFilterWrapper();

        $this->assertEquals($filter, $filter->where('foo', 'foo'));
        $this->assertEquals($filter, $filter->where('foo', true));
        $this->assertEquals($filter, $filter->where('foo', 100));
        $this->assertEquals($filter, $filter->where('foo', null));
        $this->assertEquals($filter, $filter->where('foo', ['a', false, 200, null]));
        $this->assertEquals($filter, $filter->where('foo', 1, '>='));
        $this->assertEquals($filter, $filter->where('foo', 1, '>=', 'or'));
        $this->assertEquals($filter, $filter->where([
            'bar' => 'bar',
            'bar' => true,
            'bar' => 100,
            'bar' => null,
            'bar' => ['a', false, 200, null],
        ]));
        $this->assertEquals($filter, $filter->where([
            'bar' => 'bar',
            'bar' => true,
            'bar' => 100,
            'bar' => null,
            'bar' => ['a', false, 200, null],
        ], false));
        $this->assertEquals($filter, $filter->where([
            'bar' => 'bar',
            'bar' => true,
            'bar' => 100,
            'bar' => null,
            'bar' => ['a', false, 200, null],
        ], false, 'or'));
        $this->assertEquals($filter, $filter->where(function ($filter) {
            $this->assertInstanceOf(Wrapper::class, $filter);
            $this->assertInstanceOf(WhereFilterWrapper::class, $filter);
        }));
        $this->assertEquals($filter, $filter->where(function ($filter) {
            $filter->where('baz', 'baz');
        }));
    }

    public function testWhereFilterWrapperWhereFailByMissingColumnValue()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('Missing filter column value.');

        $this->createWhereFilterWrapper()->where('foo'); // exception
    }

    public function testWhereFilterWrapperWhereFailByInvalidDefaultConnector()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('The default filter connector must be a string.');

        $this->createWhereFilterWrapper()->where(function () {
            // do nothing
        }, new stdClass()); // exception
    }

    public function testWhereFilterWrapperWhereFailByInvalidColumn()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('Invalid filter column.');

        $this->createWhereFilterWrapper()->where(new stdClass()); // exception
    }
}
