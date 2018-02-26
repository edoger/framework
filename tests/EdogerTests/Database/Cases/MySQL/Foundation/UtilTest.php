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
use Edoger\Database\MySQL\Foundation\Util;

class UtilTest extends TestCase
{
    public function testUtilWrap()
    {
        $this->assertEquals('`foo`', Util::wrap('foo'));
    }

    public function testUtilMatrixize()
    {
        $this->assertEquals([], Util::matrixize([]));
        $this->assertEquals(
            [
                'foo' => ['a', 'b'],
                'bar' => [1, 1],
            ],
            Util::matrixize([
                'foo' => ['a', 'b'],
                'bar' => [1],
            ])
        );
    }

    public function testUtilTranspose()
    {
        $this->assertEquals([], Util::transpose([]));
        $this->assertEquals(
            [
                ['a', 1],
                ['b', 2],
            ],
            Util::transpose([
                'foo' => ['a', 'b'],
                'bar' => [1, 2],
            ])
        );
    }

    public function testUtilColumnize()
    {
        $this->assertEquals('`foo`,`bar`', Util::columnize(['foo', 'bar']));
        $this->assertEquals('foo,bar', Util::columnize(['foo', 'bar'], false));
    }

    public function testUtilEnclose()
    {
        $this->assertEquals('(foo)', Util::enclose('foo'));
    }
}
