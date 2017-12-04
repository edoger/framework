<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Serializer;

use Edoger\Util\Arr;
use Edoger\Serializer\Contracts\Serializer;
use Edoger\Serializer\Exceptions\SerializerException;

class JsonSerializer implements Serializer
{
    /**
     * The serializer default encoding options.
     *
     * @var array
     */
    protected $encodeOptions = ['options' => 0, 'depth' => 512];

    /**
     * The serializer default decoding options.
     *
     * @var array
     */
    protected $decodeOptions = ['options' => 0, 'depth' => 512, 'assoc' => false];

    /**
     * The json serializer constructor.
     *
     * @param array $options       The serialization options.
     * @param array $encodeOptions The serializer default encoding options.
     * @param array $decodeOptions The serializer default decoding options.
     *
     * @return void
     */
    public function __construct(array $encodeOptions = [], array $decodeOptions = [])
    {
        if (!empty($encodeOptions)) {
            // Set the default encoding options.
            $this->encodeOptions = Arr::merge($this->encodeOptions, $encodeOptions);
        }

        if (!empty($decodeOptions)) {
            // Set the default decoding options.
            $this->decodeOptions = Arr::merge($this->decodeOptions, $decodeOptions);
        }
    }

    /**
     * Serialize the given value into a json string.
     *
     * @param mixed $value   The given value.
     * @param array $options The serializer encoding options.
     *
     * @throws Edoger\Serializer\Exception\SerializerException Thrown when serialization fails.
     *
     * @return string
     */
    public function serialize($value, array $options = []): string
    {
        $options = array_merge($this->encodeOptions, $options);
        $json    = json_encode($value, $options['options'], $options['depth']);

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
     * @param array  $options The serializer decoding options.
     *
     * @throws Edoger\Serializer\Exception\SerializerException Thrown when deserialization fails.
     *
     * @return mixed
     */
    public function deserialize(string $str, array $options = [])
    {
        $options = array_merge($this->decodeOptions, $options);
        $value   = json_decode($str, $options['assoc'], $options['depth'], $options['options']);

        if (JSON_ERROR_NONE !== $code = json_last_error()) {
            throw new SerializerException(
                sprintf('Deserialization failed: %s.', json_last_error_msg()),
                $code
            );
        }

        return $value;
    }
}
