<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Serializer\Tests\Cases;

use stdClass;
use __PHP_Incomplete_Class;
use PHPUnit\Framework\TestCase;
use Edoger\Serializer\PHPSerializer;
use Edoger\Serializer\Contracts\Serializer;
use Edoger\Serializer\Exceptions\SerializerException;

class PHPSerializerTest extends TestCase
{
    public function testPHPSerializerInstanceOfSerializer()
    {
        $serializer = new PHPSerializer();

        $this->assertInstanceOf(Serializer::class, $serializer);
    }

    public function testPHPSerializerCreate()
    {
        $serializer = PHPSerializer::create();

        $this->assertInstanceOf(Serializer::class, $serializer);
    }

    public function testPHPSerializerSerialize()
    {
        $serializer = PHPSerializer::create();

        foreach ([
            [true, 'b:1;'],
            [false, 'b:0;'],
            [null, 'N;'],
            ['string', 's:6:"string";'],
            [[], 'a:0:{}'],
            [new stdClass(), 'O:8:"stdClass":0:{}'],
            [[0, 1, 2], 'a:3:{i:0;i:0;i:1;i:1;i:2;i:2;}'],
            [[0, 1, '2'], 'a:3:{i:0;i:0;i:1;i:1;i:2;s:1:"2";}'],
            [['foo' => 'bar'],  'a:1:{s:3:"foo";s:3:"bar";}'],
        ] as $item) {
            $this->assertEquals($item[1], $serializer->serialize($item[0]));
        }
    }

    public function testPHPSerializerSerializeFail()
    {
        $this->expectException(SerializerException::class);

        // Just try an exception.
        PHPSerializer::create()->serialize(function () {});
    }

    public function testPHPSerializerDeserialize()
    {
        $serializer = PHPSerializer::create();

        foreach ([
            [true, 'b:1;'],
            [false, 'b:0;'],
            [null, 'N;'],
            ['string', 's:6:"string";'],
            [[], 'a:0:{}'],
            [new stdClass(), 'O:8:"stdClass":0:{}'],
            [[0, 1, 2], 'a:3:{i:0;i:0;i:1;i:1;i:2;i:2;}'],
            [[0, 1, '2'], 'a:3:{i:0;i:0;i:1;i:1;i:2;s:1:"2";}'],
            [['foo' => 'bar'],  'a:1:{s:3:"foo";s:3:"bar";}'],
        ] as $item) {
            $this->assertEquals($item[0], $serializer->deserialize($item[1]));
        }
    }

    public function testPHPSerializerDeserializeWithOptions()
    {
        $str = serialize(new stdClass());

        $this->assertInstanceOf(
            stdClass::class,
            PHPSerializer::create()->deserialize($str)
        );

        $this->assertInstanceOf(
            __PHP_Incomplete_Class::class,
            PHPSerializer::create()->deserialize($str, ['allowed_classes' => false])
        );

        $this->assertInstanceOf(
            __PHP_Incomplete_Class::class,
            PHPSerializer::create()->deserialize($str, ['allowed_classes' => []])
        );
    }

    public function testPHPSerializerDeserializeFail()
    {
        $this->expectException(SerializerException::class);

        // Just try an exception.
        PHPSerializer::create()->deserialize('foo');
    }
}
