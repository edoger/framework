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
use Countable;
use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Arguments;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Database\MySQL\Grammars\Filter;
use Edoger\Database\MySQL\Exceptions\GrammarException;

class FilterTest extends TestCase
{
    protected function createFilter(string $connector = 'and')
    {
        return new Filter($connector);
    }

    public function testFilterInstanceOfArrayable()
    {
        $filter = $this->createFilter();

        $this->assertInstanceOf(Arrayable::class, $filter);
    }

    public function testFilterInstanceOfCountable()
    {
        $filter = $this->createFilter();

        $this->assertInstanceOf(Countable::class, $filter);
    }

    public function testFilterIsEmpty()
    {
        $filter = $this->createFilter();

        $this->assertTrue($filter->isEmpty());
        $filter->where('foo', 'foo');
        $this->assertFalse($filter->isEmpty());
    }

    public function testFilterWhereColumnCondition()
    {
        $filter = $this->createFilter();

        $this->assertEquals($filter, $filter->where('foo', 'foo'));
        $this->assertEquals($filter, $filter->where('foo', true));
        $this->assertEquals($filter, $filter->where('foo', 100));
        $this->assertEquals($filter, $filter->where('foo', null));
        $this->assertEquals($filter, $filter->where('foo', ['bar', 'baz']));

        $this->assertEquals($filter, $filter->where('foo', 'foo', '>'));
        $this->assertEquals($filter, $filter->where('foo', 'foo', '<', 'or'));
    }

    public function testFilterWhereColumnConditionFailByMissingColumnValue()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('Missing filter column value.');

        $this->createFilter()->where('foo'); // exception
    }

    public function testFilterWhereColumnConditionFailByEmptyColumnValues()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('The filter range condition values can not be empty.');

        $this->createFilter()->where('foo', []); // exception
    }

    public function testFilterWhereColumnConditionFailByInvalidColumnValue()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('The column filter value is invalid.');

        $this->createFilter()->where('foo', new stdClass()); // exception
    }

    public function testFilterWhereMultipleColumnCondition()
    {
        $filter = $this->createFilter();

        $this->assertEquals($filter, $filter->where([]));
        $this->assertEquals($filter, $filter->where([
            'foo' => 'foo',
            'bar' => true,
            'baz' => ['a', 'b'],
            'baf' => null,
            'bah' => 100,
        ]));

        $this->assertEquals($filter, $filter->where(['foo' => 'foo']));
        $this->assertEquals($filter, $filter->where(['foo' => 'foo'], '>'));
        $this->assertEquals($filter, $filter->where(['foo' => 'foo'], '>', 'or'));
    }

    public function testFilterWhereGroupCondition()
    {
        $filter = $this->createFilter();

        $this->assertEquals($filter, $filter->where(function ($filter) {
            $this->assertInstanceOf(Filter::class, $filter);
        }));

        $this->assertEquals($filter, $filter->where(function ($filter) {
            $filter->where('foo', 'foo');
            $filter->where('bar', 'bar');
        }));

        $this->assertEquals($filter, $filter->where(function ($filter) {
            $filter->where('foo', 'foo');
        }, 'or'));
        $this->assertEquals($filter, $filter->where(function ($filter) {
            $filter->where('foo', 'foo');
        }, 'and'));
        $this->assertEquals($filter, $filter->where(function ($filter) {
            $filter->where('foo', 'foo');
        }, null));
        $this->assertEquals($filter, $filter->where(function ($filter) {
            $filter->where('foo', 'foo');
        }, null, 'or'));
    }

    public function testFilterWhereGroupConditionFailByInvalidSubFilterConnector()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('Invalid sub-filter connector.');

        $this->createFilter()->where(function ($filter) {
            // do nothing
        }, new stdClass()); // exception
    }

    public function testFilterWhereFailByInvalidFilterColumn()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('Invalid filter column.');

        $this->createFilter()->where(new stdClass(), 'foo'); // exception
    }

    public function testFilterCompile()
    {
        $filter    = $this->createFilter();
        $arguments = Arguments::create();

        $this->assertEquals('', $filter->compile($arguments));
        $filter->where('foo', 'foo');
        $this->assertEquals('`foo` = ?', $filter->compile($arguments));
        $this->assertEquals(['foo'], $arguments->toArray());

        $filter->where('bar', true, '>=', 'or');
        $this->assertEquals('`foo` = ? OR `bar` >= ?', $filter->compile($arguments->clear()));
        $this->assertEquals(['foo', 1], $arguments->toArray());
    }

    public function testFilterCompileUseMultipleWhere()
    {
        $filter    = $this->createFilter();
        $arguments = Arguments::create();

        $filter->where([
            'foo' => 'foo',
            'bar' => false,
            'baz' => null,
        ]);

        $this->assertEquals('`foo` = ? AND `bar` = ? AND `baz` IS NULL', $filter->compile($arguments));
        $this->assertEquals(['foo', 0], $arguments->toArray());
    }

    public function testFilterCompileUseWhereArray()
    {
        $filter    = $this->createFilter();
        $arguments = Arguments::create();

        $filter->where('foo', ['a', 100, true, null], false);

        $this->assertEquals('`foo` NOT IN (?,?,?,?)', $filter->compile($arguments));
        $this->assertEquals(['a', 100, 1, ''], $arguments->toArray());
    }

    public function testFilterCompileUseWhereGroup()
    {
        $filter    = $this->createFilter();
        $arguments = Arguments::create();

        $filter
            ->where('foo', 'foo')
            ->where(function ($filter) {
                $filter->where('a', 1)->where('a', 5);
            }, 'or')
            ->where('bar', '%bar%', 'like');

        $this->assertEquals('`foo` = ? AND (`a` = ? OR `a` = ?) AND `bar` LIKE ?', $filter->compile($arguments));
        $this->assertEquals(['foo', 1, 5, '%bar%'], $arguments->toArray());
    }

    public function testFilterClear()
    {
        $filter = $this->createFilter();

        $filter->where('foo', 'foo');
        $filter->where('bar', 'bar');

        $this->assertFalse($filter->isEmpty());
        $this->assertEquals($filter, $filter->clear());
        $this->assertTrue($filter->isEmpty());
    }

    public function testFilterArrayable()
    {
        $filter = $this->createFilter();

        $this->assertEquals([], $filter->toArray());

        $filter->where('foo', 'foo');
        $this->assertEquals(
            [
                [
                    'compiler'  => 'simple',
                    'column'    => 'foo',
                    'value'     => 'foo',
                    'operator'  => '=',
                    'connector' => 'AND',
                ],
            ],
            $filter->toArray()
        );
    }

    public function testFilterCountable()
    {
        $filter = $this->createFilter();

        $this->assertEquals(0, count($filter));

        $filter->where('foo', 'foo');
        $this->assertEquals(1, count($filter));
    }
}
