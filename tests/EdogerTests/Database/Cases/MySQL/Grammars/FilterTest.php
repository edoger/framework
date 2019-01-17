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
use Countable;
use Edoger\Container\Wrapper;
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
        $filter->addColumnFilter('foo', 'foo');
        $this->assertFalse($filter->isEmpty());
    }

    public function testFilterAddColumnFilter()
    {
        $filter = $this->createFilter();

        $this->assertEquals($filter, $filter->addColumnFilter('foo', 'foo'));
        $this->assertEquals($filter, $filter->addColumnFilter('foo', true));
        $this->assertEquals($filter, $filter->addColumnFilter('foo', 100));
        $this->assertEquals($filter, $filter->addColumnFilter('foo', null));
        $this->assertEquals($filter, $filter->addColumnFilter('foo', ['bar', 'baz']));
        $this->assertEquals($filter, $filter->addColumnFilter('foo', 'foo', '>'));
        $this->assertEquals($filter, $filter->addColumnFilter('foo', 'foo', '<', 'or'));
        $this->assertEquals($filter, $filter->addColumnFilter('foo', 'foo', true, 'or'));
    }

    public function testFilterAddColumnFilterFailByEmptyColumnValues()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('The filter range condition values can not be empty.');

        $this->createFilter()->addColumnFilter('foo', []); // exception
    }

    public function testFilterAddColumnFilterFailByInvalidColumnValue()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('The column filter value is invalid.');

        $this->createFilter()->addColumnFilter('foo', new stdClass()); // exception
    }

    public function testFilterAddColumnFilters()
    {
        $filter = $this->createFilter();

        $this->assertEquals($filter, $filter->addColumnFilters([]));
        $this->assertEquals($filter, $filter->addColumnFilters([
            'foo' => 'foo',
            'bar' => true,
            'baz' => ['a', 'b'],
            'baf' => null,
            'bah' => 100,
        ]));

        $this->assertEquals($filter, $filter->addColumnFilters(['foo' => 'foo']));
        $this->assertEquals($filter, $filter->addColumnFilters(['foo' => 'foo'], '>'));
        $this->assertEquals($filter, $filter->addColumnFilters(['foo' => 'foo'], '>', 'or'));
        $this->assertEquals($filter, $filter->addColumnFilters(['foo' => 'foo'], true, 'or'));
    }

    public function testFilterAddGroupFilter()
    {
        $filter  = $this->createFilter();
        $wrapper = new Wrapper($this->createFilter());

        $this->assertEquals($filter, $filter->addGroupFilter(function ($wrapper) {
            $this->assertInstanceOf(Wrapper::class, $wrapper);
            $this->assertInstanceOf(Filter::class, $wrapper->getOriginal());
        }, $wrapper));

        $this->assertEquals($filter, $filter->addGroupFilter(function ($wrapper) {
            $wrapper->getOriginal()->addColumnFilter('foo', 'foo');
            $wrapper->getOriginal()->addColumnFilter('bar', 'bar');
        }, $wrapper));

        $this->assertEquals($filter, $filter->addGroupFilter(function ($wrapper) {
            $wrapper->getOriginal()->addColumnFilter('foo', 'foo');
        }, $wrapper, 'or'));
    }

    public function testFilterCompile()
    {
        $filter    = $this->createFilter();
        $arguments = Arguments::create();

        $this->assertEquals('', $filter->compile($arguments));
        $filter->addColumnFilter('foo', 'foo');
        $this->assertEquals('`foo` = ?', $filter->compile($arguments));
        $this->assertEquals(['foo'], $arguments->toArray());

        $filter->addColumnFilter('bar', true, '>=', 'or');
        $this->assertEquals('`foo` = ? OR `bar` >= ?', $filter->compile($arguments->clear()));
        $this->assertEquals(['foo', 1], $arguments->toArray());
    }

    public function testFilterCompileUseMultipleWhere()
    {
        $filter    = $this->createFilter();
        $arguments = Arguments::create();

        $filter->addColumnFilters([
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

        $filter->addColumnFilter('foo', ['a', 100, true, null], false);

        $this->assertEquals('`foo` NOT IN (?,?,?,?)', $filter->compile($arguments));
        $this->assertEquals(['a', 100, 1, ''], $arguments->toArray());
    }

    public function testFilterCompileUseWhereGroup()
    {
        $filter    = $this->createFilter();
        $arguments = Arguments::create();
        $wrapper   = new Wrapper($this->createFilter('or'));

        $filter
            ->addColumnFilter('foo', 'foo')
            ->addGroupFilter(function ($wrapper) {
                $wrapper->getOriginal()->addColumnFilter('a', 1)->addColumnFilter('a', 5);
            }, $wrapper)
            ->addColumnFilter('bar', '%bar%', 'like');

        $this->assertEquals('`foo` = ? AND (`a` = ? OR `a` = ?) AND `bar` LIKE ?', $filter->compile($arguments));
        $this->assertEquals(['foo', 1, 5, '%bar%'], $arguments->toArray());
    }

    public function testFilterClear()
    {
        $filter = $this->createFilter();

        $filter->addColumnFilter('foo', 'foo');
        $filter->addColumnFilter('bar', 'bar');

        $this->assertFalse($filter->isEmpty());
        $this->assertEquals($filter, $filter->clear());
        $this->assertTrue($filter->isEmpty());
    }

    public function testFilterArrayable()
    {
        $filter = $this->createFilter();

        $this->assertEquals([], $filter->toArray());

        $filter->addColumnFilter('foo', 'foo');
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

        $filter->addColumnFilter('foo', 'foo');
        $this->assertEquals(1, count($filter));
    }
}
