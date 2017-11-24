<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config\Tests\Cases;

use RuntimeException;
use Edoger\Config\Config;
use Edoger\Event\Collector;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Edoger\Config\Tests\Support\TestLoader;

class ConfigTest extends TestCase
{
    public function testConfigExtendsCollector()
    {
        $config = new Config();

        $this->assertInstanceOf(Collector::class, $config);
    }

    public function testConfigFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid configuration group loader.');

        new Config([false]); // exception
    }

    public function testConfigIsEmptyLoaders()
    {
        $loader = new TestLoader();

        $config = new Config();
        $this->assertTrue($config->isEmptyLoaders());

        $config = new Config([$loader]);
        $this->assertFalse($config->isEmptyLoaders());
    }

    public function testConfigCountLoaders()
    {
        $loader = new TestLoader();

        $config = new Config();
        $this->assertEquals(0, $config->countLoaders());

        $config = new Config([$loader]);
        $this->assertEquals(1, $config->countLoaders());
    }

    public function testConfigGetLoaders()
    {
        $loaderA = new TestLoader('testA');
        $loaderB = new TestLoader('testB');

        $config = new Config();
        $this->assertEquals([], $config->getLoaders());

        $config = new Config([$loaderA]);
        $this->assertEquals([$loaderA], $config->getLoaders());

        $config = new Config([$loaderA, $loaderB]);
        $this->assertEquals([$loaderA, $loaderB], $config->getLoaders());
    }

    public function testConfigPushLoader()
    {
        $loaderA = new TestLoader('testA');
        $loaderB = new TestLoader('testB');
        $loaderC = function () {};

        $config = new Config();

        $this->assertEquals(1, $config->pushLoader($loaderA));
        $this->assertEquals(2, $config->pushLoader($loaderB));
        $this->assertEquals(3, $config->pushLoader($loaderC));
    }

    public function testConfigPopLoader()
    {
        $loaderA = new TestLoader('testA');
        $loaderB = new TestLoader('testB');
        $loaderC = new TestLoader('testC');
        $config  = new Config([$loaderA, $loaderB, $loaderC]);

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

        $config = new Config();

        $config->popLoader(); // exception
    }

    public function testConfigClearLoaders()
    {
        $loaderA = new TestLoader('testA');
        $loaderB = new TestLoader('testB');
        $loaderC = new TestLoader('testC');
        $config  = new Config([$loaderA, $loaderB, $loaderC]);

        $this->assertFalse($config->isEmptyLoaders());
        $this->assertEquals($config, $config->clearLoaders());
        $this->assertTrue($config->isEmptyLoaders());
    }
}
