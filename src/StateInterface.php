<?php

declare(strict_types=1);

namespace Rc\StateMachine;

interface StateInterface
{
    /**
     * Name of the State.
     */
    public function getName(): string;

    /**
     * String representation of the State.
     */
    public function __toString(): string;
}
