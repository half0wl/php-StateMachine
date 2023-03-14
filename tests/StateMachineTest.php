<?php

declare(strict_types=1);

namespace Halfowl\StateMachine;

use DomainException;
use RuntimeException;
use LengthException;

class StateMachineTest extends \PHPUnit\Framework\TestCase
{
    private State $green;
    private State $yellow;
    private State $red;
    private StateTransition $greenToYellow;
    private StateTransition $yellowToRedGreen;
    private StateTransition $redToGreen;

    public function setUp(): void
    {
        $this->green = new State("green");
        $this->yellow = new State("yellow");
        $this->red = new State("red");
        $this->greenToYellow = new StateTransition(
            $this->green,
            [$this->yellow],
        );
        // This is a "special" traffic light that can go from yellow to
        // green :D
        $this->yellowToRedGreen = new StateTransition(
            $this->yellow,
            [$this->red, $this->green],
        );
        $this->redToGreen = new StateTransition(
            $this->red,
            [$this->green],
        );
    }

    public function testConstructOk(): void
    {
        $sm = new StateMachine([$this->yellowToRedGreen], $this->green);

        $this->assertInstanceOf(StateMachineInterface::class, $sm);
        $this->assertEquals($sm->current(), $this->green);
    }

    public function testConstructWithEmptyTransitionsThrows(): void
    {
        $this->expectException(LengthException::class);
        $sm = new StateMachine([], new State("blah"));
    }

    public function testCurrent(): void
    {
        $sm = new StateMachine(
            transitions: [
                $this->greenToYellow,
                $this->redToGreen,
            ],
            starting: $this->green,
        );
        $this->assertEquals($sm->current(), $this->green);
    }

    public function testCan(): void
    {
        $sm = new StateMachine(
            transitions: [
                $this->greenToYellow,
                $this->redToGreen,
            ],
            starting: $this->green,
        );
        assert($sm->current() === $this->green);
        $this->assertTrue($sm->can($this->yellow));
        $this->assertFalse($sm->can($this->red));
    }

    public function testIs(): void
    {
        $sm = new StateMachine(
            transitions: [
                $this->greenToYellow,
                $this->redToGreen,
            ],
            starting: $this->green,
        );
        $this->assertTrue($sm->is($this->green));
        $this->assertFalse($sm->is($this->yellow));
    }

    public function testValidTransitionsOk(): void
    {
        $sm = new StateMachine(
            transitions: [
                $this->greenToYellow,
                $this->yellowToRedGreen,
                $this->redToGreen,
            ],
            starting: $this->green,
        );

        assert($sm->current() === $this->green);

        // Green->Yellow
        $sm->transition($this->yellow);
        $this->assertEquals($sm->current(), $this->yellow);

        // Yellow->Green
        $sm->transition($this->green);
        $this->assertEquals($sm->current(), $this->green);

        // Green->Yellow
        $sm->transition($this->yellow);
        $this->assertEquals($sm->current(), $this->yellow);

        // Yellow->Red
        $sm->transition($this->red);
        $this->assertEquals($sm->current(), $this->red);

        // Red->Green
        $sm->transition($this->green);
        $this->assertEquals($sm->current(), $this->green);
    }

    public function testInvalidTransitionIsBlocked(): void
    {
        $sm = new StateMachine(
            transitions: [
                $this->greenToYellow,
                $this->redToGreen,
            ],
            starting: $this->green,
        );

        assert($sm->current() === $this->green);

        // Green->Red is a illegal state transition
        $this->expectException(RuntimeException::class);
        $sm->transition($this->red);
    }

    public function testUnregisteredTransitionThrows(): void
    {
        $sm = new StateMachine(
            transitions: [
                $this->greenToYellow,
                // Intentionally commented out:
                //   $this->yellowToRedGreen,
                $this->redToGreen,
            ],
            starting: $this->green,
        );

        assert($sm->current() === $this->green);
        $sm->transition($this->yellow);
        assert($sm->current() === $this->yellow);

        // Error because `$this->yellowToRedGreen` is not registered
        $this->expectException(DomainException::class);
        $sm->transition($this->green);
    }
}
