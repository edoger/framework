<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Http\Server\Traits;

trait RequestPostContentSupport
{
    /**
     * The client's original request post content.
     *
     * @var string
     */
    protected $postContent;

    /**
     * Initialize the client's original request post content.
     *
     * @return void
     */
    protected function initRequestPostContentSupport(): void
    {
        // Read client's original request post content.
        // See: http://php.net/manual/en/wrappers.php.php
        $this->setPostContent((string) file_get_contents('php://input'));
    }

    /**
     * Get the client's original request post content.
     *
     * @return string
     */
    public function getPostContent(): string
    {
        return $this->postContent;
    }

    /**
     * Set the client's original request post content.
     *
     * @param string $content The client's original request post content.
     *
     * @return void
     */
    public function setPostContent(string $content): void
    {
        $this->postContent = $content;
    }
}
