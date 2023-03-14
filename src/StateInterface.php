<?php

declare(strict_types=1);

namespace Rc\StateMachine;

interface StateInterface
{
    /**
     * @return string
     *   Name of the State.
     */
    public function getName(): string;

    /**
     * @return string
     *   String representation of the State.
     */
    public function __toString(): string;

    /**
     * Registers the valid states we can transition to.
     *
     * @param State $states
     *   `$states` that this State can transition to.
     * @return State
     */
    public function registerTransitions(State ...$states): State;

    /**
     * Registers the valid states we can transition to.
     *
     * @param State $state
     *   State to check whether it's a valid transition from current State.
     * @return bool
     *   True if transitioning from current State to `$state` is allowed,
     *   False otherwise.
     */
    public function canTransitionTo(State $state): bool;
}
