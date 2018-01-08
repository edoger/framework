<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Serializer\Tests\Cases;

use stdClass;
use __PHP_Incomplete_Class;
use PHPUnit\Framework\TestCase;
use Edoger\Serializer\PhpSerializer;
use Edoger\Serializer\Contracts\Serializer;
use Edoger\Serializer\Exceptions\SerializerException;

class PhpSerializerTest extends TestCase
{
    public function testPhpSerializerIsEnabled()
    {
        $this->assertTrue(PhpSerializer::isEnabled());
    }

    public function testPhpSerializerInstanceOfSerializer()
    {
        $serializer = new PhpSerializer();

        $this->assertInstanceOf(Serializer::class, $serializer);
    }

    public function testPhpSerializerCreate()
    {
        $serializer = PhpSerializer::create();

        $this->assertInstanceOf(Serializer::class, $serializer);
    }

    public function testPhpSerializerSerialize()
    {
        $serializer = PhpSerializer::create();

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

    public function testPhpSerializerSerializeFail()
    {
        $this->expectException(SerializerException::class);

        // Just try an exception.
        PhpSerializer::create()->serialize(function () {});
    }

    public function testPhpSerializerDeserialize()
    {
        $serializer = PhpSerializer::create();

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

    public function testPhpSerializerDeserializeWithOptions()
    {
        $str = serialize(new stdClass());

        $this->assertInstanceOf(
            stdClass::class,
            PhpSerializer::create()->deserialize($str)
        );

        $this->assertInstanceOf(
            __PHP_Incomplete_Class::class,
            PhpSerializer::create()->deserialize($str, ['allowed_classes' => false])
        );

        $this->assertInstanceOf(
            __PHP_Incomplete_Class::class,
            PhpSerializer::create()->deserialize($str, ['allowed_classes' => []])
        );
    }

    public function testPhpSerializerDeserializeFail()
    {
        $this->expectException(SerializerException::class);

        // Just try an exception.
        PhpSerializer::create()->deserialize('foo');
    }
}
