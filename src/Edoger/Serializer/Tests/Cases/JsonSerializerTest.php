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
use PHPUnit\Framework\TestCase;
use Edoger\Serializer\JsonSerializer;
use Edoger\Serializer\Contracts\Serializer;
use Edoger\Serializer\Exceptions\SerializerException;

class JsonSerializerTest extends TestCase
{
    public function testJsonSerializerInstanceOfSerializer()
    {
        $serializer = new JsonSerializer();

        $this->assertInstanceOf(Serializer::class, $serializer);
    }

    public function testJsonSerializerSerialize()
    {
        $serializer = new JsonSerializer();

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
        $serializer = new JsonSerializer();

        $this->assertEquals(
            json_encode(['foo' => 'bar', 'baz'], JSON_PRETTY_PRINT),
            $serializer->serialize(['foo' => 'bar', 'baz'], ['options' => JSON_PRETTY_PRINT])
        );
    }

    public function testJsonSerializerSerializeWithDefaultOptions()
    {
        $serializer = new JsonSerializer(['options' => JSON_PRETTY_PRINT]);

        $this->assertEquals(
            json_encode(['foo' => 'bar', 'baz'], JSON_PRETTY_PRINT),
            $serializer->serialize(['foo' => 'bar', 'baz'])
        );
    }

    public function testJsonSerializerSerializeFail()
    {
        $this->expectException(SerializerException::class);
        $this->expectExceptionMessage('Serialization failed: Inf and NaN cannot be JSON encoded.');
        $this->expectExceptionCode(JSON_ERROR_INF_OR_NAN);

        $serializer = new JsonSerializer();

        // Just try an exception.
        $serializer->serialize(NAN);
    }

    public function testJsonSerializerDeserialize()
    {
        $serializer = new JsonSerializer();

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

    public function testJsonSerializerDeserializeWithOptions()
    {
        $serializer = new JsonSerializer();
        $json       = '{"num":123456789012345678901234567890}';

        $this->assertEquals(
            json_decode($json, false, 512, JSON_PRETTY_PRINT),
            $serializer->deserialize($json, ['options' => JSON_BIGINT_AS_STRING])
        );
    }

    public function testJsonSerializerDeserializeWithDefaultOptions()
    {
        $serializer = new JsonSerializer([], ['options' => JSON_BIGINT_AS_STRING]);
        $json       = '{"num":123456789012345678901234567890}';

        $this->assertEquals(
            json_decode($json, false, 512, JSON_PRETTY_PRINT),
            $serializer->deserialize($json)
        );
    }

    public function testJsonSerializerDeserializeFail()
    {
        $this->expectException(SerializerException::class);
        $this->expectExceptionMessage('Deserialization failed: Syntax error.');
        $this->expectExceptionCode(JSON_ERROR_SYNTAX);

        $serializer = new JsonSerializer();

        // Just try an exception.
        $serializer->deserialize('{foo:bar}');
    }
}
