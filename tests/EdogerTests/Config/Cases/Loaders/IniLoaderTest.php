<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Config\Cases\Loaders;

use Edoger\Config\Config;
use Edoger\Config\Repository;
use PHPUnit\Framework\TestCase;
use Edoger\Config\AbstractLoader;
use Edoger\Config\Loaders\IniLoader;
use Edoger\Config\AbstractFileLoader;

class IniLoaderTest extends TestCase
{
    protected $config;

    public static function setUpBeforeClass()
    {
        $dir = EDOGER_TESTS_TEMP;

        @file_put_contents(EDOGER_TESTS_TEMP.'/test.ini', '[test]'.PHP_EOL.'key=foo');
        @file_put_contents(EDOGER_TESTS_TEMP.'/test.suffix.ini', 'key=bar'.PHP_EOL.'temp=EDOGER_TESTS_TEMP');
        @file_put_contents(EDOGER_TESTS_TEMP.'/bad.ini', '[======');

        // Make sure the file does not exist.
        if (file_exists(EDOGER_TESTS_TEMP.'/non.ini')) {
            @unlink(EDOGER_TESTS_TEMP.'/non.ini');
        }
    }

    public static function tearDownAfterClass()
    {
        $files = ['/test.ini', '/test.suffix.ini', '/bad.ini'];

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

    protected function createIniLoader(string $suffix = null)
    {
        if (is_null($suffix)) {
            return new IniLoader(EDOGER_TESTS_TEMP);
        }

        return new IniLoader(EDOGER_TESTS_TEMP, $suffix);
    }

    public function testIniLoaderExtendsAbstractLoader()
    {
        $loader = $this->createIniLoader();

        $this->assertInstanceOf(AbstractLoader::class, $loader);
    }

    public function testIniLoaderExtendsAbstractFileLoader()
    {
        $loader = $this->createIniLoader();

        $this->assertInstanceOf(AbstractFileLoader::class, $loader);
    }

    public function testIniLoaderWithDefaultSuffix()
    {
        $this->config->pushLoader($this->createIniLoader());

        $group = $this->config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals(['test' => ['key' => 'foo']], $group->toArray());
    }

    public function testIniLoaderWithUserSuffix()
    {
        $this->config->pushLoader($this->createIniLoader('.suffix.ini'));

        $group = $this->config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals(['key' => 'bar', 'temp' => EDOGER_TESTS_TEMP], $group->toArray());
    }

    public function testIniLoaderFileNotExists()
    {
        $this->config->pushLoader($this->createIniLoader());

        $group = $this->config->group('non'); // not found

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }

    public function testIniLoaderBadFile()
    {
        $this->config->pushLoader($this->createIniLoader());

        $group = $this->config->group('bad'); // bad

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }
}
