<?php

declare(strict_types=1);

namespace Rc\StateMachine;

use TypeError;
use LengthException;

class StateTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructOk(): void
    {
        $st0 = new State("foo");
        $this->assertSame($st0->getName(), "foo");
        $this->assertSame((string)$st0, "foo");

        $st1 = new State("bar");
        $this->assertSame($st1->getName(), "bar");
        $this->assertSame((string)$st1, "bar");
    }

    public function testRegisterTransitionsOk(): void
    {
        $st0 = new State("0");
        $st1 = new State("1");

        $st0->registerTransitions($st0, $st1);
        $st1->registerTransitions($st0, $st1);
        $this->assertEquals(
            [$st0, $st1],
            $st0->getTransitionsForUnitTests(),
        );
        $this->assertEquals(
            [$st0, $st1],
            $st1->getTransitionsForUnitTests(),
        );
    }

    public function testCanTransitionToValidState(): void
    {
        $st0 = new State("0");
        $st1 = new State("1");
        $st0->registerTransitions($st0, $st1);
        $this->assertTrue($st0->canTransitionTo($st0));
        $this->assertTrue($st0->canTransitionTo($st1));
    }

    public function testCannotTransitionToInvalidState(): void
    {
        $st0 = new State("0");
        $st1 = new State("1");
        $st0->registerTransitions($st0);
        $this->assertTrue($st0->canTransitionTo($st0));
        $this->assertFalse($st0->canTransitionTo($st1));
    }

    public function testConstructBlockedOnEmptyString(): void
    {
        $this->expectException(LengthException::class);
        new State("");
    }

    /**
     * @dataProvider invalidValues
     */
    public function testConstructBlockedOnInvalidValues(mixed $value): void
    {
        $this->expectException(TypeError::class);
        new State($value);
    }

    /**
     * @return array<mixed>
     */
    public static function invalidValues()
    {
        return [
            [123,],
            [[],],
            [["foo", "bar"]],
        ];
    }
}
