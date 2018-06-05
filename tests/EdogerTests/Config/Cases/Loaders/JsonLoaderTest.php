<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Config\Cases\Loaders;

use Edoger\Config\Config;
use Edoger\Config\Repository;
use PHPUnit\Framework\TestCase;
use Edoger\Config\AbstractLoader;
use Edoger\Config\AbstractFileLoader;
use Edoger\Config\Loaders\JsonLoader;

class JsonLoaderTest extends TestCase
{
    protected $config;

    public static function setUpBeforeClass()
    {
        $dir = EDOGER_TESTS_TEMP;

        @file_put_contents(EDOGER_TESTS_TEMP.'/test.json', json_encode(['key' => 'foo']));
        @file_put_contents(EDOGER_TESTS_TEMP.'/test.suffix.json', json_encode(['key' => 'bar']));
        @file_put_contents(EDOGER_TESTS_TEMP.'/bad.json', '"bad"');
        @file_put_contents(EDOGER_TESTS_TEMP.'/error.json', 'error');

        // Make sure the file does not exist.
        if (file_exists(EDOGER_TESTS_TEMP.'/non.json')) {
            @unlink(EDOGER_TESTS_TEMP.'/non.json');
        }
    }

    public static function tearDownAfterClass()
    {
        $files = ['/test.json', '/test.suffix.json', '/bad.json', '/error.json'];

        foreach ($files as $value) {
            if (file_exists(EDOGER_TESTS_TEMP.$value)) {
                @unlink(EDOGER_TESTS_TEMP.$value);
            }
        }
    }

    protected function setUp()
    {
        $this->config = new Config();
    }

    protected function tearDown()
    {
        $this->config = null;
    }

    protected function createJsonLoader(string $suffix = null)
    {
        if (is_null($suffix)) {
            return new JsonLoader(EDOGER_TESTS_TEMP);
        }

        return new JsonLoader(EDOGER_TESTS_TEMP, $suffix);
    }

    public function testJsonLoaderExtendsAbstractLoader()
    {
        $loader = $this->createJsonLoader();

        $this->assertInstanceOf(AbstractLoader::class, $loader);
    }

    public function testJsonLoaderExtendsAbstractFileLoader()
    {
        $loader = $this->createJsonLoader();

        $this->assertInstanceOf(AbstractFileLoader::class, $loader);
    }

    public function testJsonLoaderWithDefaultSuffix()
    {
        $this->config->pushLoader($this->createJsonLoader());

        $group = $this->config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals(['key' => 'foo'], $group->toArray());
    }

    public function testJsonLoaderWithUserSuffix()
    {
        $this->config->pushLoader($this->createJsonLoader('.suffix.json'));

        $group = $this->config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals(['key' => 'bar'], $group->toArray());
    }

    public function testJsonLoaderFileNotExists()
    {
        $this->config->pushLoader($this->createJsonLoader());

        $group = $this->config->group('non'); // not found

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }

    public function testJsonLoaderBadFile()
    {
        $this->config->pushLoader($this->createJsonLoader());

        $group = $this->config->group('bad'); // bad

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals(['bad' => 'bad'], $group->toArray());
    }

    public function testJsonLoaderErrorFile()
    {
        $this->config->pushLoader($this->createJsonLoader());

        $group = $this->config->group('error');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }
}
