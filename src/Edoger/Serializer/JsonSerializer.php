<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Serializer;

use Edoger\Serializer\Contracts\Serializer;
use Edoger\Serializer\Exceptions\SerializerException;

class JsonSerializer implements Serializer
{
    /**
     * Bitmask of JSON encode options.
     *
     * @var int
     */
    protected $encodeOptions = 0;

    /**
     * Set the maximum depth. Must be greater than zero.
     *
     * @var int
     */
    protected $encodeDepth = 512;

    /**
     * When TRUE, returned objects will be converted into associative arrays.
     *
     * @var bool
     */
    protected $associative = false;

    /**
     * User specified recursion depth.
     *
     * @var int
     */
    protected $decodeDepth = 512;

    /**
     * Bitmask of JSON decode options.
     *
     * @var int
     */
    protected $decodeOptions = 0;

    /**
     * The json serializer constructor.
     *
     * @param array $options The serialization options.
     *
     * @return void
     */
    public function __construct(array $options = [])
    {
        if (!empty($options)) {
            $merged = array_merge([
                'encodeOptions' => 0,
                'encodeDepth'   => 512,
                'associative'   => false,
                'decodeDepth'   => 512,
                'decodeOptions' => 0,
            ], $options);

            $this->encodeOptions = (int) $merged['encodeOptions'];
            $this->encodeDepth   = (int) $merged['encodeDepth'];
            $this->associative   = (bool) $merged['associative'];
            $this->decodeDepth   = (int) $merged['decodeDepth'];
            $this->decodeOptions = (int) $merged['decodeOptions'];
        }
    }

    /**
     * Serialize the given value into a string.
     *
     * @param mixed $value The given value.
     *
     * @throws Edoger\Serializer\Exception\SerializerException Thrown when serialization fails.
     *
     * @return string
     */
    public function serialize($value): string
    {
        $json = json_encode($value, $this->encodeOptions, $this->encodeDepth);

        if (JSON_ERROR_NONE !== $code = json_last_error()) {
            throw new SerializerException(
                'Serialization failed: '.json_last_error_msg(),
                $code
            );
        }

        return $json;
    }

    /**
     * Deserialize the given string.
     *
     * @param string $str The given string.
     *
     * @throws Edoger\Serializer\Exception\SerializerException Thrown when deserialization fails.
     *
     * @return mixed
     */
    public function deserialize(string $str)
    {
        $value = json_decode($str, $this->associative, $this->decodeDepth, $this->decodeOptions);

        if (JSON_ERROR_NONE !== $code = json_last_error()) {
            throw new SerializerException(
                'Deserialization failed: '.json_last_error_msg(),
                $code
            );
        }

        return $value;
    }
}
