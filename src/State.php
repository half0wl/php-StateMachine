<?php

declare(strict_types=1);

namespace Rc\StateMachine;

use DomainException;

use function is_string;

final class State implements StateInterface
{
    private string $name;

    public function __construct(string $name)
    {
        if (!is_string($name)) {
            throw new DomainException();
        }
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
