<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config\Tests\Cases;

use Countable;
use Edoger\Config\Repository;
use Edoger\Util\Contracts\Arrayable;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    public function testRepositoryInstanceOfArrayable()
    {
        $repository = new Repository();

        $this->assertInstanceOf(Arrayable::class, $repository);
    }

    public function testRepositoryInstanceOfCountable()
    {
        $repository = new Repository();

        $this->assertInstanceOf(Countable::class, $repository);
    }

    public function testRepositoryIsEmpty()
    {
        $repository = new Repository([]);
        $this->assertTrue($repository->isEmpty());

        $repository = new Repository(['a']);
        $this->assertFalse($repository->isEmpty());
    }

    public function testRepositoryHas()
    {
        $repository = new Repository([
            'a' => 1,
            'b' => [
                1,
                'm' => 1,
                'n' => null,
                'o' => ['k' => 1],
            ],
            'c' => null,
        ]);

        $this->assertTrue($repository->has('a'));
        $this->assertTrue($repository->has('b'));
        $this->assertTrue($repository->has('c'));
        $this->assertTrue($repository->has('b.0'));
        $this->assertTrue($repository->has('b.m'));
        $this->assertTrue($repository->has('b.n'));
        $this->assertTrue($repository->has('b.o'));
        $this->assertTrue($repository->has('b.o.k'));
        $this->assertFalse($repository->has('non'));
        $this->assertFalse($repository->has('b.non'));
        $this->assertFalse($repository->has('b.o.non'));
    }

    public function testRepositoryGet()
    {
        $items = [
            'a' => 1,
            'b' => [
                1,
                'm' => 1,
                'n' => null,
                'o' => ['k' => 1],
            ],
            'c' => null,
        ];
        $repository = new Repository($items);

        $this->assertEquals($items['a'], $repository->get('a'));
        $this->assertEquals($items['b'], $repository->get('b'));
        $this->assertEquals($items['c'], $repository->get('c'));
        $this->assertEquals($items['b'][0], $repository->get('b.0'));
        $this->assertEquals($items['b']['m'], $repository->get('b.m'));
        $this->assertEquals($items['b']['n'], $repository->get('b.n'));
        $this->assertEquals($items['b']['o'], $repository->get('b.o'));
        $this->assertEquals($items['b']['o']['k'], $repository->get('b.o.k'));
        $this->assertNull($repository->get('non'));
        $this->assertNull($repository->get('b.non'));
        $this->assertNull($repository->get('b.o.non'));
        $this->assertEquals(1, $repository->get('non', 1));
        $this->assertEquals(1, $repository->get('b.non', 1));
        $this->assertEquals(1, $repository->get('b.o.non', 1));
    }

    public function testRepositoryArrayable()
    {
        $items      = ['k' => 'v'];
        $repository = new Repository($items);

        $this->assertEquals($items, $repository->toArray());
    }

    public function testRepositoryCountable()
    {
        $this->assertEquals(0, count(new Repository([])));
        $this->assertEquals(1, count(new Repository([1])));
    }
}
