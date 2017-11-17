<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Response;

use Edoger\Http\Foundation\Collection;
use Edoger\Http\Foundation\StatusCodes;
use Edoger\Http\Server\Traits\ResponseCookiesSupport;
use Edoger\Http\Server\Traits\ResponseHeadersSupport;
use InvalidArgumentException;

class Response
{
    use ResponseHeadersSupport, ResponseCookiesSupport;

    /**
     * The HTTP response status code.
     *
     * @var integer
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
     * @param  integer  $status  The HTTP response status code.
     * @param  iterable $content The response content.
     * @param  iterable $headers The response headers.
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
     * @return integer
     */
    public function getStatusCode(): int
    {
        return $this->status;
    }

    /**
     * Sets the HTTP response status code.
     *
     * @param  integer                  $status The HTTP response status code.
     * @throws InvalidArgumentException Thrown when the HTTP status code is invalid.
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
     * @param  iterable $content The HTTP response content.
     * @return self
     */
    public function withResponseContent(iterable $content): self
    {
        if ($this->content) {
            // This is to ensure that the collection referenced by the renderer are always the same.
            // When the application resets the response content,
            // all the renderers immediately receive the new content.
            $this->content->replace($content);
        } else {
            $this->content = new Collection($content);
        }

        return $this;
    }
}
