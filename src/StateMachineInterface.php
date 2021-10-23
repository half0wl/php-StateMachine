<?php

declare(strict_types=1);

namespace Rc\StateMachine;

interface StateMachineInterface
{
    /**
     * @return non-empty-string
     *   The current state of the StateMachine.
     */
    public function current(): string;

    /**
     * Is current() equal to $state?
     *
     * @param non-empty-string $state
     *   State to compare current() against.
     */
    public function is(string $state): bool;

    /**
     * Can we transition from current() to $next?
     * @NOTE It's possible for the StateMachine to remain in its current state,
     * so this MUST return true if `current() === $next`.
     *
     * @param non-empty-string $next
     *   State to check if it's a valid transition from current().
     */
    public function can(string $next): bool;

    /**
     * Transition the StateMachine to the $next state.
     *
     * @param non-empty-string $next
     *   Next state to transition to.
     * @throws \DomainException
     *   If $next is an empty string.
     * @throws \RuntimeException
     *   If $next is not a valid transition from current().
     */
    public function next(string $next): void;
}
