<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2020 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Serializer;

use RuntimeException;
use Edoger\Serializer\Contracts\Serializer;
use Edoger\Serializer\Exceptions\SerializerException;

class JsonSerializer implements Serializer
{
    /**
     * The json serializer constructor.
     *
     * @codeCoverageIgnore
     *
     * @throws RuntimeException Thrown when the "json" extension is not enabled.
     *
     * @return void
     */
    public function __construct()
    {
        if (!extension_loaded('json')) {
            throw new RuntimeException('The "json" extension is not loaded or does not exist.');
        }
    }

    /**
     * Create a json serializer.
     *
     * @return self
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * Serialize the given value into a json string.
     *
     * @param mixed $value   The given value.
     * @param int   $options The serializer encoding options.
     * @param int   $depth   The maximum depth.
     *
     * @throws SerializerException Thrown when serialization fails.
     *
     * @return string
     */
    public function serialize($value, int $options = 0, int $depth = 512): string
    {
        $json = json_encode($value, $options, $depth);

        if (JSON_ERROR_NONE !== $code = json_last_error()) {
            throw new SerializerException(
                sprintf('Serialization failed: %s.', json_last_error_msg()),
                $code
            );
        }

        return $json;
    }

    /**
     * Deserialize the given json.
     *
     * @param string $str     The given json.
     * @param bool   $assoc   Whether to convert the object into an associative array.
     * @param int    $depth   User specified recursion depth.
     * @param int    $options The serializer decoding options.
     *
     * @throws SerializerException Thrown when deserialization fails.
     *
     * @return mixed
     */
    public function deserialize(string $str, bool $assoc = false, int $depth = 512, int $options = 0)
    {
        $value = json_decode($str, $assoc, $depth, $options);

        if (JSON_ERROR_NONE !== $code = json_last_error()) {
            throw new SerializerException(
                sprintf('Deserialization failed: %s.', json_last_error_msg()),
                $code
            );
        }

        return $value;
    }
}
