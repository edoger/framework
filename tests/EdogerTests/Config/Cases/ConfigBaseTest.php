<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Config\Cases;

use RuntimeException;
use Edoger\Config\Config;
use Edoger\Event\Collector;
use PHPUnit\Framework\TestCase;
use EdogerTests\Config\Mocks\TestLoader;

class ConfigBaseTest extends TestCase
{
    protected function createConfig(iterable $loaders = [])
    {
        return new Config($loaders);
    }

    protected function createLoader(string $group = 'test', array $value = [])
    {
        return new TestLoader($group, $value);
    }

    public function testConfigExtendsCollector()
    {
        $config = $this->createConfig();

        $this->assertInstanceOf(Collector::class, $config);
    }

    public function testConfigIsEmptyLoaders()
    {
        $config = $this->createConfig();
        $this->assertTrue($config->isEmptyLoaders());

        $config = $this->createConfig([$this->createLoader()]);
        $this->assertFalse($config->isEmptyLoaders());
    }

    public function testConfigCountLoaders()
    {
        $config = $this->createConfig();
        $this->assertEquals(0, $config->countLoaders());

        $config = $this->createConfig([$this->createLoader()]);
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
        $loaderA = $this->createLoader('testA');
        $loaderB = $this->createLoader('testB');
        $loaderC = function () {};

        $config = $this->createConfig();

        $this->assertEquals(1, $config->pushLoader($loaderA));
        $this->assertEquals(2, $config->pushLoader($loaderB));
        $this->assertEquals(3, $config->pushLoader($loaderC));
    }

    public function testConfigPopLoader()
    {
        $loaderA = $this->createLoader('testA');
        $loaderB = $this->createLoader('testB');
        $loaderC = $this->createLoader('testC');
        $config  = $this->createConfig([$loaderA, $loaderB, $loaderC]);

        $this->assertEquals($loaderC, $config->popLoader());
        $this->assertEquals([$loaderA, $loaderB], $config->getLoaders());

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
        $loaderA = $this->createLoader('testA');
        $loaderB = $this->createLoader('testB');
        $loaderC = $this->createLoader('testC');
        $config  = $this->createConfig([$loaderA, $loaderB, $loaderC]);

        $this->assertFalse($config->isEmptyLoaders());
        $this->assertEquals($config, $config->clearLoaders());
        $this->assertTrue($config->isEmptyLoaders());
    }
}
