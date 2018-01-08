<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Config\Tests\Cases\Loaders;

use Edoger\Config\Config;
use Edoger\Config\Repository;
use PHPUnit\Framework\TestCase;
use Edoger\Config\AbstractLoader;
use Edoger\Config\Loaders\JsonLoader;

class JsonLoaderTest extends TestCase
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

    public function testJsonLoaderExtendsAbstractLoader()
    {
        $loader = new JsonLoader('foo');

        $this->assertInstanceOf(AbstractLoader::class, $loader);
    }

    public function testJsonLoaderWithDefaultSuffix()
    {
        $loader = new JsonLoader($this->dir.'/json');
        $this->config->pushLoader($loader);
        $group = $this->config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals(['key' => 'value'], $group->toArray());
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    }

    public function testJsonLoaderWithUserSuffix()
    {
        $loader = new JsonLoader($this->dir.'/json', '.config.json');
        $this->config->pushLoader($loader);
        $group = $this->config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals(['key' => 1], $group->toArray());
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    }

    public function testJsonLoaderFileNotExists()
    {
        $loader = new JsonLoader($this->dir.'/json');
        $this->config->pushLoader($loader);
        $group = $this->config->group('non'); // not found

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    }

    public function testJsonLoaderBadFile()
    {
        $loader = new JsonLoader($this->dir.'/json');
        $this->config->pushLoader($loader);
        $group = $this->config->group('bad'); // bad

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
        $this->assertNotEquals(JSON_ERROR_NONE, json_last_error());
    }
}
