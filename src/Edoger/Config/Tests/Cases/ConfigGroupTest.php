<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config\Tests\Cases;

use Edoger\Config\Config;
use Edoger\Config\Repository;
use PHPUnit\Framework\TestCase;
use Edoger\Config\Tests\Support\TestLoader;
use Edoger\Config\Tests\Support\TestExceptionLoader;

class ConfigGroupTest extends TestCase
{
    public function testConfigGroupWithoutLoader()
    {
        $config = new Config();

        $group = $config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }

    public function testConfigGroupReloadWithoutLoader()
    {
        $config = new Config();

        $groupA = $config->group('test');
        $groupB = $config->group('test');
        $groupC = $config->group('test', true);

        $this->assertEquals(spl_object_hash($groupA), spl_object_hash($groupB));
        $this->assertNotEquals(spl_object_hash($groupB), spl_object_hash($groupC));
    }

    public function testConfigGroupWithLoader()
    {
        $loaderA = new TestLoader('testA', ['test' => 'A']);
        $loaderB = new TestLoader('testB', ['test' => 'B']);

        $config = new Config([$loaderA, $loaderB]);

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
        $loader = new TestExceptionLoader();
        $config = new Config([$loader]);

        $group = $config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }

    public function testConfigGroupReloadWithLoader()
    {
        $loader = new TestLoader('test', ['test']);
        $config = new Config([$loader]);

        $groupA = $config->group('test');
        $groupB = $config->group('test');
        $groupC = $config->group('test', true);

        $this->assertEquals(spl_object_hash($groupA), spl_object_hash($groupB));
        $this->assertNotEquals(spl_object_hash($groupB), spl_object_hash($groupC));
    }

    public function testConfigGroupByEmptyName()
    {
        $loader = new TestLoader('test', ['test']);
        $config = new Config([$loader]);

        $group = $config->group('');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }
}
