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
