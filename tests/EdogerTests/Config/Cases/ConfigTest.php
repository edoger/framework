<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Config\Cases;

use Exception;
use RuntimeException;
use Edoger\Config\Config;
use Edoger\Config\Repository;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use EdogerTests\Config\Mocks\TestLoader;
use EdogerTests\Config\Mocks\TestExceptionLoader;
use Edoger\Config\Contracts\Config as ConfigContract;

class ConfigTest extends TestCase
{
    protected function createConfig($loaders = [])
    {
        if (!is_array($loaders)) {
            $loaders = [$loaders];
        }

        return new Config($loaders);
    }

    protected function createLoader(string $group = 'test', array $items = [])
    {
        return new TestLoader($group, $items);
    }

    protected function createExceptionLoader()
    {
        return new TestExceptionLoader();
    }

    public function testConfigInstanceOfConfigContract()
    {
        $config = $this->createConfig();

        $this->assertInstanceOf(ConfigContract::class, $config);
    }

    public function testConfigIsEmptyLoaders()
    {
        $config = $this->createConfig();
        $this->assertTrue($config->isEmptyLoaders());

        $config = $this->createConfig($this->createLoader());
        $this->assertFalse($config->isEmptyLoaders());
    }

    public function testConfigCountLoaders()
    {
        $config = $this->createConfig();
        $this->assertEquals(0, $config->countLoaders());

        $config = $this->createConfig($this->createLoader());
        $this->assertEquals(1, $config->countLoaders());
    }

    public function testConfigGetLoaders()
    {
        $loaderA = $this->createLoader('testA');
        $loaderB = $this->createLoader('testB');

        $config = $this->createConfig();
        $this->assertEquals([], $config->getLoaders());

        $config = $this->createConfig([$loaderA]);
        $this->assertEquals([$loaderA], $config->getLoaders());

        $config = $this->createConfig([$loaderA, $loaderB]);
        $this->assertEquals([$loaderA, $loaderB], $config->getLoaders());
    }

    public function testConfigPushLoader()
    {
        $config = $this->createConfig();

        $loaderA = $this->createLoader('testA');
        $loaderB = function () {};

        $this->assertEquals(1, $config->pushLoader($loaderA));
        $this->assertEquals(2, $config->pushLoader($loaderB));
    }

    public function testConfigPushLoaderFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid configuration group loader.');

        $this->createConfig()->pushLoader(null); // exception
    }

    public function testConfigPopLoader()
    {
        $loaderA = $this->createLoader('testA');
        $loaderB = $this->createLoader('testB');

        $config = $this->createConfig([$loaderA, $loaderB]);

        $this->assertEquals($loaderB, $config->popLoader());
        $this->assertEquals([$loaderA], $config->getLoaders());
        $this->assertEquals($loaderA, $config->popLoader());
        $this->assertEquals([], $config->getLoaders());
    }

    public function testConfigPopLoaderFail()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Unable to remove loader from the empty loader stack.'
        );

        $this->createConfig()->popLoader(); // exception
    }

    public function testConfigClearLoaders()
    {
        $config = $this->createConfig([
            $this->createLoader('testA'),
            $this->createLoader('testB'),
        ]);

        $this->assertFalse($config->isEmptyLoaders());
        $this->assertEquals($config, $config->clearLoaders());
        $this->assertTrue($config->isEmptyLoaders());
    }

    public function testConfigGroupWithoutLoader()
    {
        $config = $this->createConfig();
        $group  = $config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
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

    public function testConfigGroupReload()
    {
        $config = $this->createConfig($this->createLoader('test', ['foo' => 'foo']));
        $group  = $config->group('test');

        $config->pushLoader($this->createLoader('test', ['bar' => 'bar']));

        $this->assertEquals($group, $config->group('test'));
        $this->assertNotEquals($group, $config->group('test', true));
    }

    public function testConfigGroupWithExceptionLoader()
    {
        $config = $this->createConfig($this->createExceptionLoader());
        $group  = $config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }
}
