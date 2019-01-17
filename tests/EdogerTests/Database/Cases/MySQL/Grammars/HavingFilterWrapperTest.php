<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL\Grammars;

use stdClass;
use Edoger\Container\Wrapper;
use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Grammars\Filter;
use Edoger\Database\MySQL\Exceptions\GrammarException;
use Edoger\Database\MySQL\Grammars\HavingFilterWrapper;
use Edoger\Database\MySQL\Grammars\Traits\HavingGrammarSupport;

class HavingFilterWrapperTest extends TestCase
{
    protected function createHavingFilterWrapper(string $connector = 'and')
    {
        return new HavingFilterWrapper($connector);
    }

    public function testHavingFilterWrapperExtendsWrapper()
    {
        $filter = $this->createHavingFilterWrapper();

        $this->assertInstanceOf(Wrapper::class, $filter);
    }

    public function testHavingFilterWrapperUseTraitWhereGrammarSupport()
    {
        $uses     = class_uses($this->createHavingFilterWrapper());
        $abstract = HavingGrammarSupport::class;

        $this->assertArrayHasKey($abstract, $uses);
        $this->assertEquals($abstract, $uses[$abstract]);
    }

    public function testHavingFilterWrapperCreateHavingFilterWrapper()
    {
        $filter = $this->createHavingFilterWrapper();

        $this->assertInstanceOf(Wrapper::class, $filter->createHavingFilterWrapper('and'));
        $this->assertInstanceOf(HavingFilterWrapper::class, $filter->createHavingFilterWrapper('or'));
    }

    public function testHavingFilterWrapperGetHavingFilter()
    {
        $filter = $this->createHavingFilterWrapper();

        $this->assertInstanceOf(Filter::class, $filter->getHavingFilter());
        $this->assertEquals($filter->getOriginal(), $filter->getHavingFilter());
    }

    public function testHavingFilterWrapperHaving()
    {
        $filter = $this->createHavingFilterWrapper();

        $this->assertEquals($filter, $filter->having('foo', 'foo'));
        $this->assertEquals($filter, $filter->having('foo', true));
        $this->assertEquals($filter, $filter->having('foo', 100));
        $this->assertEquals($filter, $filter->having('foo', null));
        $this->assertEquals($filter, $filter->having('foo', ['a', false, 200, null]));
        $this->assertEquals($filter, $filter->having('foo', 1, '>='));
        $this->assertEquals($filter, $filter->having('foo', 1, '>=', 'or'));
        $this->assertEquals($filter, $filter->having([
            'bar' => 'bar',
            'bar' => true,
            'bar' => 100,
            'bar' => null,
            'bar' => ['a', false, 200, null],
        ]));
        $this->assertEquals($filter, $filter->having([
            'bar' => 'bar',
            'bar' => true,
            'bar' => 100,
            'bar' => null,
            'bar' => ['a', false, 200, null],
        ], false));
        $this->assertEquals($filter, $filter->having([
            'bar' => 'bar',
            'bar' => true,
            'bar' => 100,
            'bar' => null,
            'bar' => ['a', false, 200, null],
        ], false, 'or'));
        $this->assertEquals($filter, $filter->having(function ($filter) {
            $this->assertInstanceOf(Wrapper::class, $filter);
            $this->assertInstanceOf(HavingFilterWrapper::class, $filter);
        }));
        $this->assertEquals($filter, $filter->having(function ($filter) {
            $filter->having('baz', 'baz');
        }));
    }

    public function testHavingFilterWrapperHavingFailByMissingColumnValue()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('Missing filter column value.');

        $this->createHavingFilterWrapper()->having('foo'); // exception
    }

    public function testHavingFilterWrapperHavingFailByInvalidDefaultConnector()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('The default filter connector must be a string.');

        $this->createHavingFilterWrapper()->having(function () {
            // do nothing
        }, new stdClass()); // exception
    }

    public function testHavingFilterWrapperHavingFailByInvalidColumn()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('Invalid filter column.');

        $this->createHavingFilterWrapper()->having(new stdClass()); // exception
    }
}
