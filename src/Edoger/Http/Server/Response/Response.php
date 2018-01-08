<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Response;

use Edoger\Util\Validator;
use InvalidArgumentException;
use Edoger\Util\Contracts\Arrayable;
use Edoger\Http\Foundation\Collection;
use Edoger\Http\Foundation\StatusCodes;
use Edoger\Http\Server\Traits\ResponseCookiesSupport;
use Edoger\Http\Server\Traits\ResponseHeadersSupport;
use Edoger\Http\Server\Traits\ResponseRendererSupport;

class Response implements Arrayable
{
    use ResponseHeadersSupport, ResponseCookiesSupport, ResponseRendererSupport;

    /**
     * The HTTP response status code.
     *
     * @var int
     */
    protected $status;

    /**
     * The HTTP response content collection.
     *
     * @var Edoger\Http\Foundation\Collection
     */
    protected $content;

    /**
     * The response constructor.
     *
     * @param int      $status  The HTTP response status code.
     * @param iterable $content The response content.
     * @param iterable $headers The response headers.
     *
     * @return void
     */
    public function __construct(int $status, iterable $content, iterable $headers = [])
    {
        $this->setStatusCode($status);
        $this->withResponseContent($content);

        // Initialize the response headers collection.
        $this->initResponseHeadersSupport($headers);
    }

    /**
     * Gets the HTTP response status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->status;
    }

    /**
     * Sets the HTTP response status code.
     *
     * @param int $status The HTTP response status code.
     *
     * @throws InvalidArgumentException Thrown when the HTTP status code is invalid.
     *
     * @return void
     */
    public function setStatusCode(int $status): void
    {
        if (!StatusCodes::isValid($status)) {
            throw new InvalidArgumentException('Invalid response HTTP status code.');
        }

        $this->status = $status;
    }

    /**
     * Get the HTTP response content collection.
     *
     * @return Edoger\Http\Foundation\Collection
     */
    public function getResponseContent(): Collection
    {
        return $this->content;
    }

    /**
     * Set the HTTP response content collection.
     *
     * @param iterable $content The HTTP response content.
     *
     * @return self
     */
    public function withResponseContent(iterable $content): self
    {
        if (is_null($this->content)) {
            $this->content = new Collection($content);
        } else {
            // This is to ensure that the collection referenced by the renderer are always the same.
            // When the application resets the response content,
            // all the renderers immediately receive the new content.
            $this->content->replace($content);
        }

        return $this;
    }

    /**
     * Clear the HTTP response content collection.
     *
     * @return self
     */
    public function clearResponseContent(): self
    {
        $this->getResponseContent()->clear();

        return $this;
    }

    /**
     * Send the response headers to the client.
     *
     * @codeCoverageIgnore
     *
     * @return self
     */
    public function sendHeaders(): self
    {
        if (headers_sent()) {
            return $this;
        }

        // Send HTTP response status code.
        if (http_response_code() !== $status = $this->getStatusCode()) {
            http_response_code($status);
        }

        // Sends HTTP response headers (excluding cookie headers).
        foreach ($this->getHeaders() as $name => $header) {
            header(
                implode('-', array_map('ucfirst', explode('-', $name))).': '.$header,
                true,
                $status
            );
        }

        // Send cookie headers.
        foreach ($this->getCookies() as $cookie) {
            // Handle cookie expiration date.
            if (0 !== $expire = $cookie->getExpiresTime()) {
                $expire += time();
            }

            setcookie(
                $cookie->getName(),
                $cookie->getValue(),
                $expire,
                $cookie->getPath(),
                $cookie->getDomain(),
                $cookie->isSecure(),
                $cookie->isHttpOnly()
            );
        }

        return $this;
    }

    /**
     * Send the response body to the client.
     *
     * @return self
     */
    public function sendBody(): self
    {
        $body = $this->getResponseRenderer()->render($this->getResponseContent());

        // Output only when the response body is not empty.
        if (Validator::isNotEmptyString($body)) {
            echo $body;
        }

        return $this;
    }

    /**
     * Returns the current response instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->getResponseContent()->toArray();
    }
}
