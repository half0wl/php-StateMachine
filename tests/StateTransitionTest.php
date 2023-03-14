<?php

declare(strict_types=1);

namespace Halfowl\StateMachine;

class StateTransitionTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructOk(): void
    {
        $s0 = new State("foo");
        $s1 = new State("bar");
        $s2 = new State("xyz");
        $t0 = new StateTransition($s0, [$s1, $s2]);

        $this->assertInstanceOf(StateTransition::class, $t0);
        $this->assertEquals($t0->src(), $s0);
        $this->assertEquals($t0->dsts(), [$s1, $s2]);
    }

    public function testInDstFound(): void
    {
        $s0 = new State("foo");
        $s1 = new State("bar");
        $s2 = new State("xyz");
        $t0 = new StateTransition($s0, [$s1, $s2]);

        $this->assertTrue($t0->inDst($s1));
        $this->assertTrue($t0->inDst($s2));
    }

    public function testInDstNotFound(): void
    {
        $s0 = new State("foo");
        $t0 = new StateTransition($s0, [$s0]);

        $this->assertFalse($t0->inDst(new State("blah")));
    }
}
