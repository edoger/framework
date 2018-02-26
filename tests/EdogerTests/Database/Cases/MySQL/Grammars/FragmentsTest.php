<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL\Grammars;

use PHPUnit\Framework\TestCase;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Database\MySQL\Grammars\Fragments;
use Edoger\Database\MySQL\Exceptions\GrammarException;

class FragmentsTest extends TestCase
{
    protected function createFragments($fragments = [])
    {
        return new Fragments($fragments);
    }

    public function testFragmentsConstructorFail()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('Invalid statement fragment.');

        $this->createFragments([false]); // exception
    }

    public function testFragmentsInstanceOfArrayable()
    {
        $fragments = $this->createFragments();

        $this->assertInstanceOf(Arrayable::class, $fragments);
    }

    public function testFragmentsCreate()
    {
        $fragments = Fragments::create();

        $this->assertInstanceOf(Fragments::class, $fragments);
    }

    public function testFragmentsIsEmpty()
    {
        $fragments = $this->createFragments();
        $this->assertTrue($fragments->isEmpty());

        $fragments = $this->createFragments(['foo']);
        $this->assertFalse($fragments->isEmpty());
    }

    public function testFragmentsPush()
    {
        $fragments = $this->createFragments();

        $this->assertEquals($fragments, $fragments->push('foo'));
        $this->assertFalse($fragments->isEmpty());
    }

    public function testFragmentsPop()
    {
        $fragments = $this->createFragments();

        $this->assertEquals('', $fragments->pop());

        $fragments->push('foo');
        $fragments->push('bar');

        $this->assertEquals('foo', $fragments->pop());
        $this->assertEquals('bar', $fragments->pop());
        $this->assertEquals('', $fragments->pop());
    }

    public function testFragmentsClear()
    {
        $fragments = $this->createFragments();

        $fragments->push('foo');
        $fragments->push('bar');

        $this->assertEquals($fragments, $fragments->clear());
        $this->assertTrue($fragments->isEmpty());
    }

    public function testFragmentsAssemble()
    {
        $fragments = $this->createFragments();

        $this->assertEquals('', $fragments->assemble());

        $fragments->push('foo');
        $this->assertEquals('foo', $fragments->assemble());

        $fragments->push('bar');
        $this->assertEquals('foo bar', $fragments->assemble());
    }

    public function testFragmentsArrayable()
    {
        $fragments = $this->createFragments();

        $this->assertEquals([], $fragments->toArray());

        $fragments->push('foo');
        $fragments->push('bar');

        $this->assertEquals(['foo', 'bar'], $fragments->toArray());
    }
}
