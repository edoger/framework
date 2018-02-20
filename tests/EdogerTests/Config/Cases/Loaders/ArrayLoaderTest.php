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
use Edoger\Config\Loaders\ArrayLoader;

class ArrayLoaderTest extends TestCase
{
    protected $config;

    public static function setUpBeforeClass()
    {
        $dir = EDOGER_TESTS_TEMP;

        @file_put_contents($dir.'/test.php', '<?php'.PHP_EOL.'return ["key" => "foo"];');
        @file_put_contents($dir.'/test.suffix.php', '<?php'.PHP_EOL.'return ["key" => "bar"];');
        @file_put_contents($dir.'/bad.php', '<?php'.PHP_EOL.'return "bad";');

        // Make sure the file does not exist.
        if (file_exists(EDOGER_TESTS_TEMP.'/non.php')) {
            @unlink(EDOGER_TESTS_TEMP.'/non.php');
        }
    }

    public static function tearDownAfterClass()
    {
        $files = ['/test.php', '/test.suffix.php', '/bad.php'];

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

    protected function createArrayLoader(string $suffix = null)
    {
        if (is_null($suffix)) {
            return new ArrayLoader(EDOGER_TESTS_TEMP);
        }

        return new ArrayLoader(EDOGER_TESTS_TEMP, $suffix);
    }

    public function testArrayLoaderExtendsAbstractLoader()
    {
        $loader = $this->createArrayLoader();

        $this->assertInstanceOf(AbstractLoader::class, $loader);
    }

    public function testArrayLoaderExtendsAbstractFileLoader()
    {
        $loader = $this->createArrayLoader();

        $this->assertInstanceOf(AbstractFileLoader::class, $loader);
    }

    public function testArrayLoaderWithDefaultSuffix()
    {
        $this->config->pushLoader($this->createArrayLoader());

        $group = $this->config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals(['key' => 'foo'], $group->toArray());
    }

    public function testArrayLoaderWithUserSuffix()
    {
        $this->config->pushLoader($this->createArrayLoader('.suffix.php'));

        $group = $this->config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals(['key' => 'bar'], $group->toArray());
    }

    public function testArrayLoaderLoadNonExistentFile()
    {
        $this->config->pushLoader($this->createArrayLoader());

        $group = $this->config->group('non'); // not found

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }

    public function testArrayLoaderLoadBadFile()
    {
        $this->config->pushLoader($this->createArrayLoader());

        $group = $this->config->group('bad'); // bad

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals(['bad' => 'bad'], $group->toArray());
    }
}
