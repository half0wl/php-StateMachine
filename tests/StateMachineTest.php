<?php

declare(strict_types=1);

namespace Rc\StateMachine;

use RuntimeException;
use LengthException;

/**
 * @covers Rc\StateMachine\StateMachine
 */
class StateMachineTest extends \PHPUnit\Framework\TestCase
{
    private State $green;
    private State $yellow;
    private State $red;

    public function setUp(): void
    {
        // A bare example modelled after traffic lights.
        $this->green = new State("green");
        $this->yellow = new State("yellow");
        $this->red = new State("red");

        $this->green->registerTransitions($this->yellow);
        $this->red->registerTransitions($this->green);

        // This is a "special" traffic light that can go from Yellow->Green :D
        $this->yellow->registerTransitions($this->green, $this->red);
    }

    public function testConstructOk(): void
    {
        $sm = new StateMachine(
            states: [$this->green, $this->yellow, $this->red],
            starting: $this->green,
        );
        $this->assertInstanceOf(StateMachineInterface::class, $sm);
        $this->assertEquals($sm->current(), $this->green);
    }

    public function testConstructWithEmptyStatesThrows(): void
    {
        $this->expectException(LengthException::class);
        $sm = new StateMachine([], new State("blah"));
    }

    public function testValidTransitionsOk(): void
    {
        $sm = new StateMachine(
            states: [$this->green, $this->yellow, $this->red],
            starting: $this->green,
        );

        assert($sm->current() === $this->green);

        // Green->Yellow
        $yel = $sm->next($this->yellow);
        $this->assertEquals($sm->current(), $yel);

        // Yellow->Green
        $grn = $sm->next($this->green);
        $this->assertEquals($sm->current(), $grn);

        // Green->Yellow
        $yel2 = $sm->next($this->yellow);
        $this->assertEquals($sm->current(), $yel2);

        // Yellow->Red
        $red = $sm->next($this->red);
        $this->assertEquals($sm->current(), $red);

        // Red->Green
        $grn2 = $sm->next($this->green);
        $this->assertEquals($sm->current(), $grn2);
    }

    public function testInvalidTransitionIsBlocked(): void
    {
        $sm = new StateMachine(
            states: [$this->green, $this->yellow, $this->red],
            starting: $this->green,
        );

        assert($sm->current() === $this->green);

        // Green->Red is a illegal state transition
        $this->expectException(RuntimeException::class);
        $sm->next($this->red);
    }
}
