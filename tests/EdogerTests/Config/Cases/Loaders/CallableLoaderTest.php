<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Config\Cases\Loaders;

use Closure;
use Edoger\Config\Config;
use Edoger\Config\Repository;
use PHPUnit\Framework\TestCase;
use Edoger\Config\AbstractLoader;
use Edoger\Config\Loaders\CallableLoader;

class CallableLoaderTest extends TestCase
{
    protected $config;

    protected function setUp()
    {
        $this->config = new Config();
    }

    protected function tearDown()
    {
        $this->config = null;
    }

    public function testCallableLoaderExtendsAbstractLoader()
    {
        $loader = new CallableLoader(function () {});

        $this->assertInstanceOf(AbstractLoader::class, $loader);
    }

    public function testCallableLoaderLoad()
    {
        $loader = new CallableLoader(function (string $group, bool $reload, Closure $next) {
            if ('test' === $group) {
                return new Repository([true]);
            }

            return $next();
        });

        $this->config->pushLoader($loader);

        $group = $this->config->group('test');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([true], $group->toArray());

        $group = $this->config->group('non');

        $this->assertInstanceOf(Repository::class, $group);
        $this->assertEquals([], $group->toArray());
    }
}
