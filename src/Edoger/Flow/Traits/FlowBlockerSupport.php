<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Flow\Traits;

use Edoger\Flow\Contracts\Blocker;

trait FlowBlockerSupport
{
    /**
     * The flow blocker.
     *
     * @var Edoger\Flow\Contracts\Blocker
     */
    protected $blocker;

    /**
     * Initialize flow blocker support.
     *
     * @param Edoger\Flow\Contracts\Blocker $blocker The flow blocker.
     *
     * @return void
     */
    protected function initFlowBlockerSupport(Blocker $blocker): void
    {
        $this->blocker = $blocker;
    }

    /**
     * Get current flow blocker.
     *
     * @return Edoger\Flow\Contracts\Blocker
     */
    public function getFlowBlocker(): Blocker
    {
        return $this->blocker;
    }
}
