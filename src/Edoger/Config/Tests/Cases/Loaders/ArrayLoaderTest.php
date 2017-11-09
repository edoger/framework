<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config\Tests\Cases\Loaders;

use Edoger\Config\AbstractLoader;
use Edoger\Config\Config;
use Edoger\Config\Loaders\ArrayLoader;
use Edoger\Config\Repository;
use PHPUnit\Framework\TestCase;

class ArrayLoaderTest extends TestCase
{
    protected $config;
    protected $dir;

    protected function setUp()
    {
        $this->config = new Config();
        $this->dir    = realpath(__DIR__.'/../../data');
    }

    protected function tearDown()
    {
        $this->config = null;
        $this->dir    = null;
    }

    public function testArrayLoaderExtendsAbstractLoader()
    {
        $loader = new ArrayLoader('foo');

        $this->assertInstanceOf(AbstractLoader::class, $loader);
    }

    public function testArrayLoaderWithDefaultSuffix()
    {
        $loader = new ArrayLoader($this->dir.'/php');
        $this->config->pushLoader($loader);
        $group = $this->config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals(['key' => 'value'], $group->toArray());
    }

    public function testArrayLoaderWithUserSuffix()
    {
        $loader = new ArrayLoader($this->dir.'/php', '.config.php');
        $this->config->pushLoader($loader);
        $group = $this->config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals(['key' => 1], $group->toArray());
    }

    public function testArrayLoaderFileNotExists()
    {
        $loader = new ArrayLoader($this->dir.'/php');
        $this->config->pushLoader($loader);
        $group = $this->config->group('non'); // not found

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }

    public function testArrayLoaderBadFile()
    {
        $loader = new ArrayLoader($this->dir.'/php');
        $this->config->pushLoader($loader);
        $group = $this->config->group('bad'); // bad

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }
}
