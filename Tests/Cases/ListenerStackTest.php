<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Event\Tests\Cases;

use Edoger\Container\Stack;
use Edoger\Event\ListenerStack;
use PHPUnit\Framework\TestCase;

class ListenerStackTest extends TestCase
{
    public function testListenerStackExtendsStack()
    {
        $stack = new ListenerStack();

        $this->assertInstanceOf(Stack::class, $stack);
    }

    public function testListenerStackIsEnabled()
    {
        $stack = new ListenerStack();

        $this->assertTrue($stack->isEnabled());
    }

    public function testListenerStackEnable()
    {
        $stack = new ListenerStack();

        $this->assertEquals($stack, $stack->enable());
        $this->assertTrue($stack->isEnabled());
    }

    public function testListenerStackDisable()
    {
        $stack = new ListenerStack();
        
        $this->assertEquals($stack, $stack->disable());
        $this->assertFalse($stack->isEnabled());
    }
}
