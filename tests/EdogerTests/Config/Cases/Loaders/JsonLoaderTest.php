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
use Edoger\Config\Loaders\JsonLoader;
use Edoger\Serializer\Exceptions\SerializerException;

class JsonLoaderTest extends TestCase
{
    protected $config;
    protected $dir;

    public static function setUpBeforeClass()
    {
        @file_put_contents(
            EDOGER_TESTS_TEMP.'/test.json',
            json_encode(['key' => 'foo'])
        );
        @file_put_contents(
            EDOGER_TESTS_TEMP.'/test.suffix.json',
            json_encode(['key' => 'bar'])
        );
        @file_put_contents(
            EDOGER_TESTS_TEMP.'/bad.json',
            '"bad"'
        );
        @file_put_contents(
            EDOGER_TESTS_TEMP.'/error.json',
            '[[[[['
        );
    }

    public static function tearDownAfterClass()
    {
        foreach (['/test.json', '/test.suffix.json', '/bad.json', '/error.json'] as $value) {
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

    protected function createJsonLoader(string $suffix = null)
    {
        if (is_null($suffix)) {
            return new JsonLoader($this->dir);
        }

        return new JsonLoader($this->dir, $suffix);
    }

    public function testJsonLoaderExtendsAbstractLoader()
    {
        $loader = $this->createJsonLoader();

        $this->assertInstanceOf(AbstractLoader::class, $loader);
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
        $error = false;

        $this->config->pushLoader($this->createJsonLoader());
        $this->config->on('error', function ($event, $dispatcher) use (&$error) {
            $error = true;
            $this->assertInstanceOf(SerializerException::class, $event->get('exception'));
        });

        $group = $this->config->group('error'); // error

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());

        $this->assertTrue($error);
    }
}
