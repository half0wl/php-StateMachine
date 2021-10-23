<?php

declare(strict_types=1);

namespace Rc\StateMachine;

use RuntimeException;
use LengthException;
use DomainException;

final class StateMachine implements StateMachineInterface
{
    /**
     * @var non-empty-string
     *   Holds the current state of the StateMachine.
     */
    private string $currentState;

    /**
     * @param array<string, array<string>> $validTransitions
     *   Valid transitions. Array should be in the shape of:
     *   `[ current_state => [ allowed_transitions_from_current_state ], ... ]`,
     *   i.e. the values are states that the key can transit to.
     *
     *   E.g. a traffic light can go from "green" to "yellow" to "red," and
     *   back to "green," but it cannot go from "green" to "red" immediately,
     *   or "red" to "yellow". We can define the valid transitions as:
     *   ```php
     *   [
     *     "green" => [
     *       "yellow"
     *     ],
     *     "yellow" => [
     *       "red",
     *     ],
     *     "red" => [
     *       "green",
     *     ],
     *   ]
     *   ```
     *
     * @param non-empty-string $starting
     *   The starting state of the StateMachine.
     *
     * @throws \LengthException
     *   If $validTransitions is an empty array.
     *
     * @throws \DomainException
     *   If $starting is an empty string, or undefined in $validTransitions.
     */
    public function __construct(
        private array $validTransitions,
        string $starting,
    ) {
        if (count($validTransitions) <= 0) {
            throw new LengthException('validTransitions must not be empty');
        }
        /**
         * @phpstan-ignore-next-line
         * This comparison results in a phpstan error because $starting is
         * typehinted as a non-empty-string.
         */
        if ('' === $starting) {
            throw new DomainException('Starting state must not be empty');
        }
        if (!in_array($starting, array_keys($validTransitions), true)) {
            throw new DomainException(
                'Starting state must be defined in validTransitions'
            );
        }
        // @TODO Further validation of validTransitions shape
        $this->currentState = $starting;
    }

    /**
     * {@inheritdoc}
     */
    public function current(): string
    {
        return $this->currentState;
    }

    /**
     * {@inheritdoc}
     */
    public function is(string $state): bool
    {
        return $this->current() === $state;
    }

    /**
     * {@inheritdoc}
     */
    public function can(string $next): bool
    {
        if ($next === $this->current()) {
            return true;
        }
        $validTransitions = $this->validTransitions[$this->current()];
        return in_array($next, $validTransitions, true);
    }

    /**
     * {@inheritdoc}
     */
    public function next(string $next): void
    {
        /**
         * @phpstan-ignore-next-line
         * This comparison results in a phpstan error because $next is
         * typehinted as a non-empty-string.
         */
        if ('' === $next) {
            throw new DomainException('Next state must not be empty');
        }
        if (!$this->can($next)) {
            $err = sprintf(
                'Illegal state transition: %s -> %s',
                $this->current(),
                $next,
            );
            throw new RuntimeException($err);
        }
        $this->currentState = $next;
    }
}
