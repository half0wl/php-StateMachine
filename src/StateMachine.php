<?php

declare(strict_types=1);

namespace Halfowl\StateMachine;

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
     * Valid StateTransitions in this StateMachine.
     *
     * @var StateTransitionInterface[]
     */
    private array $transitions = [];

    /**
     * Create a new StateMachine.
     *
     * @param State $starting
     *   The starting State of this StateMachine.
     * @param StateTransition[] $transitions
     *   Valid StateTransitions in this StateMachine.
     * @throws LengthException
     *   If `$transitions` is empty.
     */
    public function __construct(array $transitions, State $starting)
    {
        if (empty($transitions)) {
            throw new LengthException("Transitions cannot be empty");
        }
        $this->setCurrent($starting);
        $this->transitions = $transitions;
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
    public function can(State $next): bool
    {
        return $this->findTransitionBySrc($this->current)->inDst($next);
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
    public function transition(State $next): void
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
    }

    /**
     * Sets the current State to `$to`.
     *
     * @param State $to
     */
    private function setCurrent(State $to): void
    {
        $this->current = $to;
    }

    /**
     * Returns the transition associated with `$src`.
     *
     * @param State $src
     * @return StateTransition
     * @throws DomainException
     *   If the transition does not exist for the given `$src`.
     */
    private function findTransitionBySrc(State $src): StateTransition
    {
        $result = array_reduce(
            $this->transitions,
            function ($carry, $obj) {
                return $carry ?? (
                    (string)$obj->src() === (string)$this->current
                        ? $obj
                        : $carry
                );
            },
            null,
        );
        if ($result === null) {
            throw new DomainException(
                "No transition registered for state '" . (string)$src . "'"
            );
        }
        return $result;
    }
}
