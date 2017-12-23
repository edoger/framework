<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Serializer;

use Throwable;
use Edoger\Serializer\Contracts\Serializer;
use Edoger\Serializer\Exceptions\SerializerException;

class PHPSerializer implements Serializer
{
    /**
     * The php serializer constructor.
     *
     * @return void
     */
    public function __construct()
    {
        // do nothing
    }

    /**
     * Create a php serializer.
     *
     * @return self
     */
    public static function create(): self
    {
        return new static();
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
        try {
            $str = serialize($value);
        } catch (Throwable $e) {
            throw new SerializerException(
                sprintf('Serialization failed: %s.', $e->getMessage()),
                $e->getCode(),
                $e
            );
        }

        return $str;
    }

    /**
     * Deserialize the given string.
     *
     * @param string $str     The given string.
     * @param array  $options The serializer decoding options.
     *
     * @throws Edoger\Serializer\Exception\SerializerException Thrown when deserialization fails.
     *
     * @return mixed
     */
    public function deserialize(string $str, array $options = [])
    {
        $value = @unserialize($str, $options);

        if (false === $value && 'b:0;' !== $str) {
            $message = 'Unknown error.';
            $code    = 0;

            if ($error = error_get_last()) {
                $message = $error['message'];
                $code    = $error['type'];

                // Clear the most recent error.
                error_clear_last();
            }

            throw new SerializerException(sprintf('Deserialization failed: %s.', $message), $code);
        }

        return $value;
    }
}
