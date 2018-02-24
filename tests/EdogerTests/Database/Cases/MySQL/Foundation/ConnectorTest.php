<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace EdogerTests\Database\Cases\MySQL\Foundation;

use PHPUnit\Framework\TestCase;
use Edoger\Database\MySQL\Foundation\Connector;
use Edoger\Database\MySQL\Exceptions\GrammarException;

class ConnectorTest extends TestCase
{
    public function testConnectorIsValid()
    {
        $this->assertTrue(Connector::isValid('and'));
        $this->assertTrue(Connector::isValid('or'));
        $this->assertFalse(Connector::isValid('foo'));
    }

    public function testConnectorStandardize()
    {
        $this->assertEquals('AND', Connector::standardize('and'));
        $this->assertEquals('OR', Connector::standardize('or'));
    }

    public function testConnectorStandardizeFail()
    {
        $this->expectException(GrammarException::class);
        $this->expectExceptionMessage('The given filter connector "foo" is invalid.');

        Connector::standardize('foo'); // exception
    }
}
