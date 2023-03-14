<?php

declare(strict_types=1);

namespace Halfowl\StateMachine;

interface StateMachineInterface
{
    /**
     * @return State
     *   The current State of the StateMachine.
     */
    public function current(): State;

    /**
     * Can we transition from the current State to the `$next` State?
     *
     * @param State $next
     *   State to check whether it's a valid transition from current State.
     * @return bool
     *   True if transitioning from current State to `$next` State is allowed,
     *   False otherwise.
     */
    public function can(State $next): bool;

    /**
     * Is the current State equal to `$state`?
     *
     * @param State $state
     *   State to compare the current State against.
     * @return bool
     *   True if current State is equal to `$state`, False otherwise.
     */
    public function is(State $state): bool;

    /**
     * Transition the StateMachine to the `$next` State, returning the `$next`
     * State.
     *
     * @param State $next
     *   Next State to transition the StateMachine to.
     * @throws \RuntimeException
     *   If `$next` is not a valid transition from current.
     */
    public function transition(State $next): void;
}
