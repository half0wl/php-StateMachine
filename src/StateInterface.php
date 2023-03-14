<?php

declare(strict_types=1);

namespace Halfowl\StateMachine;

interface StateInterface
{
    /**
     * @return string
     *   Name of this State object.
     */
    public function getName(): string;

    /**
     * @return string
     *   String representation of this State object.
     */
    public function __toString(): string;
}
