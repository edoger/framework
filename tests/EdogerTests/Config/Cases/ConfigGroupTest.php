<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Config\Cases;

use Edoger\Config\Config;
use Edoger\Config\Repository;
use PHPUnit\Framework\TestCase;
use EdogerTests\Config\Mocks\TestLoader;
use EdogerTests\Config\Mocks\TestExceptionLoader;

class ConfigGroupTest extends TestCase
{
    protected function createConfig(iterable $loaders = [])
    {
        return new Config($loaders);
    }

    protected function createLoader(string $group = 'test', array $value = [])
    {
        return new TestLoader($group, $value);
    }

    public function testConfigGroupWithoutLoader()
    {
        $config = $this->createConfig();
        $group  = $config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }

    public function testConfigGroupReload()
    {
        $config = $this->createConfig([$this->createLoader('test', [true])]);

        $groupA = $config->group('test');
        $groupB = $config->group('test');

        $config->pushLoader($this->createLoader('test', [false]));

        $groupC = $config->group('test', true);

        $this->assertEquals($groupA, $groupB);
        $this->assertNotEquals($groupB, $groupC);
    }

    public function testConfigGroupWithLoader()
    {
        $config = $this->createConfig([
            $this->createLoader('testA', ['test' => 'A']),
            $this->createLoader('testB', ['test' => 'B']),
        ]);

        $groupA = $config->group('testA');
        $groupB = $config->group('testB');
        $groupC = $config->group('testC'); // not found

        $this->assertInstanceOf(Repository::class, $groupA);
        $this->assertInstanceOf(Repository::class, $groupB);
        $this->assertInstanceOf(Repository::class, $groupC);

        $this->assertEquals(['test' => 'A'], $groupA->toArray());
        $this->assertEquals(['test' => 'B'], $groupB->toArray());
        $this->assertEquals([], $groupC->toArray());
    }

    public function testConfigGroupWithExceptionLoader()
    {
        $config = $this->createConfig([new TestExceptionLoader()]);
        $group  = $config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }
}
