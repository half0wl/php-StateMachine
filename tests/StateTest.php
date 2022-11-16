<?php

declare(strict_types=1);

namespace Rc\StateMachine;

use TypeError;

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

    /**
     * @dataProvider invalidValues
     */
    public function testConstructThrowsWithInvalidValues(mixed $value): void
    {
        $this->expectException(TypeError::class);
        new State($value);
    }

    /**
     * @return array<mixed>
     */
    public function invalidValues()
    {
        return [
            [123,],
            [[],],
            [["foo", "bar"]],
        ];
    }
}
