<?php

declare(strict_types=1);

namespace Rc\StateMachine;

use RuntimeException;
use LengthException;
use DomainException;

final class StateMachine implements StateMachineInterface
{
    /**
     * The current State of this StateMachine.
     */
    private State $current;

    /**
     * Valid States in this StateMachine.
     *
     * @var State[]
     */
    private array $states;

    /**
     * Create a new StateMachine.
     *
     * @param State[] $states
     *   States for this StateMachine.
     * @param State $starting
     *   The starting State for this StateMachine.
     */
    public function __construct(array $states, State $starting)
    {
        if (empty($states) === true) {
            throw new LengthException("Received an empty set of states");
        }
        $this->states = $states;
        $this->current = $starting;
    }

    /**
     * {@inheritdoc}
     */
    public function is(State $state): bool
    {
        return $this->current() === $state;
    }

    /**
     * {@inheritdoc}
     */
    public function can(State $next): bool
    {
        return $this->current()->canTransitionTo($next);
    }

    /**
     * {@inheritdoc}
     */
    public function current(): State
    {
        return $this->current;
    }

    /**
     * {@inheritdoc}
     */
    public function next(State $next): State
    {
        if (!$this->can($next)) {
            $err = sprintf(
                'Illegal state transition: %s -> %s',
                (string)$this->current(),
                (string)$next,
            );
            throw new RuntimeException($err);
        }
        $this->setCurrent($next);
        return $this->current();
    }

    /**
     * Sets the current State to `$to`.
     */
    private function setCurrent(State $to): void
    {
        $this->current = $to;
    }
}
