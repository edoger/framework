<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2019 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Config\Cases\Loaders;

use Edoger\Config\Config;
use Edoger\Config\Repository;
use PHPUnit\Framework\TestCase;
use Edoger\Config\AbstractLoader;
use Edoger\Config\Loaders\XmlLoader;
use Edoger\Config\AbstractFileLoader;

class XmlLoaderTest extends TestCase
{
    protected $config;

    public static function setUpBeforeClass()
    {
        $dir = EDOGER_TESTS_TEMP;

        @file_put_contents($dir.'/test.xml', '<config><key>foo</key></config>');
        @file_put_contents($dir.'/test.suffix.xml', '<config><key>bar</key></config>');
        @file_put_contents($dir.'/bad.xml', 'bad');

        // Make sure the file does not exist.
        if (file_exists(EDOGER_TESTS_TEMP.'/non.xml')) {
            @unlink(EDOGER_TESTS_TEMP.'/non.xml');
        }
    }

    public static function tearDownAfterClass()
    {
        $files = ['/test.xml', '/test.suffix.xml', '/bad.xml'];

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

    protected function createXmlLoader(string $suffix = null)
    {
        if (is_null($suffix)) {
            return new XmlLoader(EDOGER_TESTS_TEMP);
        }

        return new XmlLoader(EDOGER_TESTS_TEMP, $suffix);
    }

    public function testXmlLoaderExtendsAbstractLoader()
    {
        $loader = $this->createXmlLoader();

        $this->assertInstanceOf(AbstractLoader::class, $loader);
    }

    public function testXmlLoaderExtendsAbstractFileLoader()
    {
        $loader = $this->createXmlLoader();

        $this->assertInstanceOf(AbstractFileLoader::class, $loader);
    }

    public function testXmlLoaderWithDefaultSuffix()
    {
        $this->config->pushLoader($this->createXmlLoader());

        $group = $this->config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals(['key' => 'foo'], $group->toArray());
    }

    public function testXmlLoaderWithUserSuffix()
    {
        $this->config->pushLoader($this->createXmlLoader('.suffix.xml'));

        $group = $this->config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals(['key' => 'bar'], $group->toArray());
    }

    public function testXmlLoaderLoadNonExistentFile()
    {
        $this->config->pushLoader($this->createXmlLoader());

        $group = $this->config->group('non'); // not found

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }

    public function testXmlLoaderLoadBadFile()
    {
        $this->config->pushLoader($this->createXmlLoader());

        $group = $this->config->group('bad'); // bad

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }
}
