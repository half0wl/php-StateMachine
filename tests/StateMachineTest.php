<?php

declare(strict_types=1);

namespace Rc\StateMachine;

use RuntimeException;
use LengthException;
use DomainException;

/**
 * @covers Rc\StateMachine\StateMachine
 */
class StateMachineTest extends \PHPUnit\Framework\TestCase
{
    private const TRAFFIC_LIGHT_GREEN = "green";
    private const TRAFFIC_LIGHT_YELLOW = "yellow";
    private const TRAFFIC_LIGHT_RED = "red";
    private const TRAFFIC_LIGHTS = [
        self::TRAFFIC_LIGHT_GREEN => [
            self::TRAFFIC_LIGHT_YELLOW,
        ],
        self::TRAFFIC_LIGHT_YELLOW => [
            self::TRAFFIC_LIGHT_RED,
        ],
        self::TRAFFIC_LIGHT_RED => [
            self::TRAFFIC_LIGHT_GREEN,
        ],
    ];

    public function testConstructWithValidParams(): void
    {
        $sm = new StateMachine(validTransitions: self::TRAFFIC_LIGHTS, starting: self::TRAFFIC_LIGHT_GREEN);
        $this->assertEquals(
            $sm->current(),
            self::TRAFFIC_LIGHT_GREEN,
            "Starting state should be green",
        );
        $this->assertTrue(
            $sm->is(self::TRAFFIC_LIGHT_GREEN),
            "Starting state should be green",
        );
    }

    public function testConstructWithEmptyTransitionsThrows(): void
    {
        $this->expectException(LengthException::class);
        $sm = new StateMachine([], "foo");
    }

    public function testConstructWithEmptyStartingStateThrows(): void
    {
        $this->expectException(DomainException::class);
        /** @phpstan-ignore-next-line */
        $sm = new StateMachine(self::TRAFFIC_LIGHTS, "");
    }

    public function testConstructOnUndefinedStartingStateThrows(): void
    {
        $this->expectException(DomainException::class);
        $sm = new StateMachine(self::TRAFFIC_LIGHTS, "Foobar");
    }

    public function testCanStayInSameState(): void
    {
        $sm = new StateMachine(self::TRAFFIC_LIGHTS, self::TRAFFIC_LIGHT_GREEN);
        assert($sm->is(self::TRAFFIC_LIGHT_GREEN));
        assert($sm->current() === self::TRAFFIC_LIGHT_GREEN);

        $this->assertTrue(
            $sm->can(self::TRAFFIC_LIGHT_GREEN),
            "Should be able to stay in the same state",
        );
        $sm->next(self::TRAFFIC_LIGHT_GREEN);
        $this->assertTrue(
            $sm->can(self::TRAFFIC_LIGHT_GREEN),
            "Should stay in the same state",
        );
        $this->assertTrue(
            $sm->is(self::TRAFFIC_LIGHT_GREEN),
            "Should stay in the same state",
        );
        $this->assertEquals(
            $sm->current(),
            self::TRAFFIC_LIGHT_GREEN,
            "Should stay in the same state",
        );
    }

    public function testValidTransitions(): void
    {
        // Starting: TRAFFIC_LIGHT_GREEN
        $sm = new StateMachine(self::TRAFFIC_LIGHTS, self::TRAFFIC_LIGHT_GREEN);
        assert($sm->current() === self::TRAFFIC_LIGHT_GREEN);

        // Green -> Yellow
        $this->assertTrue(
            $sm->can(self::TRAFFIC_LIGHT_YELLOW),
            "Should be able to transition to next valid state",
        );
        $sm->next(self::TRAFFIC_LIGHT_YELLOW);
        $this->assertEquals(
            $sm->current(),
            self::TRAFFIC_LIGHT_YELLOW,
            "Should have transitioned to next valid state",
        );
        $this->assertTrue(
            $sm->is(self::TRAFFIC_LIGHT_YELLOW),
            "Should have transitioned to next valid state",
        );

        // Yellow -> Red
        $this->assertTrue(
            $sm->can(self::TRAFFIC_LIGHT_RED),
            "Should be able to transition to next state",
        );
        $sm->next(self::TRAFFIC_LIGHT_RED);
        $this->assertEquals(
            $sm->current(),
            self::TRAFFIC_LIGHT_RED,
            "Should have transitioned to next state",
        );
        $this->assertTrue(
            $sm->is(self::TRAFFIC_LIGHT_RED),
            "Should have transitioned to next state",
        );

        // Red -> Green
        $this->assertTrue(
            $sm->can(self::TRAFFIC_LIGHT_GREEN),
            "Should be able to transition to next state",
        );
        $sm->next(self::TRAFFIC_LIGHT_GREEN);
        $this->assertEquals(
            $sm->current(),
            self::TRAFFIC_LIGHT_GREEN,
            "Should have transitioned to next state",
        );
        $this->assertTrue(
            $sm->is(self::TRAFFIC_LIGHT_GREEN),
            "Should have transitioned to next state",
        );
    }

    public function testEmptyTransitionThrows(): void
    {
        // Starting: TRAFFIC_LIGHT_GREEN
        $sm = new StateMachine(self::TRAFFIC_LIGHTS, self::TRAFFIC_LIGHT_GREEN);
        assert($sm->current() === self::TRAFFIC_LIGHT_GREEN);
        $this->expectException(DomainException::class);
        /** @phpstan-ignore-next-line */
        $sm->next("");
    }

    public function testInvalidTransitionThrows(): void
    {
        // Starting: TRAFFIC_LIGHT_GREEN
        $sm = new StateMachine(self::TRAFFIC_LIGHTS, self::TRAFFIC_LIGHT_GREEN);
        assert($sm->current() === self::TRAFFIC_LIGHT_GREEN);

        // Green -> Red, illegal
        $this->assertFalse(
            $sm->can(self::TRAFFIC_LIGHT_RED),
            "Should not be able to transition to next invalid state",
        );
        $this->expectException(RuntimeException::class);
        $sm->next(self::TRAFFIC_LIGHT_RED);
    }
}
