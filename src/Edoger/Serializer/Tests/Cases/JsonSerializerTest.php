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
use RuntimeException;
use PHPUnit\Framework\TestCase;
use Edoger\Serializer\JsonSerializer;
use Edoger\Serializer\Contracts\Serializer;
use Edoger\Serializer\Exceptions\SerializerException;
use Edoger\Serializer\Tests\Support\DisabledJsonSerializer;

class JsonSerializerTest extends TestCase
{
    public function testJsonSerializerConstructorFail()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The "json" extension is not loaded or does not exist.');

        new DisabledJsonSerializer(); // exception
    }

    public function testJsonSerializerIsEnabled()
    {
        if (extension_loaded('json')) {
            $this->assertTrue(JsonSerializer::isEnabled());
        } else {
            $this->assertFalse(JsonSerializer::isEnabled());
        }
    }

    public function testJsonSerializerInstanceOfSerializer()
    {
        $serializer = new JsonSerializer();

        $this->assertInstanceOf(Serializer::class, $serializer);
    }

    public function testJsonSerializerCreate()
    {
        $serializer = JsonSerializer::create();

        $this->assertInstanceOf(Serializer::class, $serializer);
    }

    public function testJsonSerializerSerialize()
    {
        $serializer = JsonSerializer::create();

        foreach ([
            [true, 'true'],
            [false, 'false'],
            [null, 'null'],
            ['string', '"string"'],
            [[], '[]'],
            [new stdClass(), '{}'],
            [[0, 1, 2], '[0,1,2]'],
            [[0, 1, '2'], '[0,1,"2"]'],
            [['foo' => 'bar'], '{"foo":"bar"}'],
        ] as $item) {
            $this->assertEquals($item[1], $serializer->serialize($item[0]));
        }
    }

    public function testJsonSerializerSerializeWithOptions()
    {
        $this->assertEquals(
            json_encode(['foo' => 'bar', 'baz'], JSON_PRETTY_PRINT),
            JsonSerializer::create()->serialize(['foo' => 'bar', 'baz'], JSON_PRETTY_PRINT)
        );
    }

    public function testJsonSerializerSerializeFail()
    {
        $this->expectException(SerializerException::class);

        // Just try an exception.
        JsonSerializer::create()->serialize(NAN);
    }

    public function testJsonSerializerDeserialize()
    {
        $serializer = JsonSerializer::create();

        foreach ([
            [true, 'true'],
            [false, 'false'],
            [null, 'null'],
            ['string', '"string"'],
            [[], '[]'],
            [new stdClass(), '{}'],
            [[0, 1, 2], '[0,1,2]'],
            [[0, 1, '2'], '[0,1,"2"]'],
        ] as $item) {
            $this->assertEquals($item[0], $serializer->deserialize($item[1]));
        }
    }

    public function testJsonSerializerDeserializeWithAssoc()
    {
        $json = '{"key":"value"}';

        $this->assertEquals(
            ['key' => 'value'],
            JsonSerializer::create()->deserialize($json, true)
        );
    }

    public function testJsonSerializerDeserializeWithOptions()
    {
        $json = '{"num":1234567890123456789012345678901234567890}';

        $this->assertEquals(
            json_decode($json, false, 512, JSON_BIGINT_AS_STRING),
            JsonSerializer::create()->deserialize($json, false, 512, JSON_BIGINT_AS_STRING)
        );
    }

    public function testJsonSerializerDeserializeFail()
    {
        $this->expectException(SerializerException::class);

        // Just try an exception.
        JsonSerializer::create()->deserialize('{foo:bar}');
    }
}
