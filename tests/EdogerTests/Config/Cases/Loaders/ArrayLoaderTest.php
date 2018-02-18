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
use Edoger\Config\Loaders\ArrayLoader;

class ArrayLoaderTest extends TestCase
{
    protected $config;
    protected $dir;

    public static function setUpBeforeClass()
    {
        @file_put_contents(
            EDOGER_TESTS_TEMP.'/test.php',
            '<?php'.PHP_EOL.'return ["key" => "foo"];'
        );
        @file_put_contents(
            EDOGER_TESTS_TEMP.'/test.suffix.php',
            '<?php'.PHP_EOL.'return ["key" => "bar"];'
        );
        @file_put_contents(
            EDOGER_TESTS_TEMP.'/bad.php',
            '<?php'.PHP_EOL.'return "bad";'
        );
    }

    public static function tearDownAfterClass()
    {
        foreach (['/test.php', '/test.suffix.php', '/bad.php'] as $value) {
            if (file_exists(EDOGER_TESTS_TEMP.$value)) {
                @unlink(EDOGER_TESTS_TEMP.$value);
            }
        }
    }

    protected function setUp()
    {
        $this->config = new Config();
        $this->dir    = EDOGER_TESTS_TEMP;
    }

    protected function tearDown()
    {
        $this->config = null;
        $this->dir    = null;
    }

    protected function createArrayLoader(string $suffix = null)
    {
        if (is_null($suffix)) {
            return new ArrayLoader($this->dir);
        }

        return new ArrayLoader($this->dir, $suffix);
    }

    public function testArrayLoaderExtendsAbstractLoader()
    {
        $loader = $this->createArrayLoader();

        $this->assertInstanceOf(AbstractLoader::class, $loader);
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

    public function testArrayLoaderFileNotExists()
    {
        $this->config->pushLoader($this->createArrayLoader());

        $group = $this->config->group('non'); // not found

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }

    public function testArrayLoaderBadFile()
    {
        $this->config->pushLoader($this->createArrayLoader());

        $group = $this->config->group('bad'); // bad

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals(['bad' => 'bad'], $group->toArray());
    }
}
