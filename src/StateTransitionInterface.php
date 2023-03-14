<?php

declare(strict_types=1);

namespace Halfowl\StateMachine;

interface StateTransitionInterface
{
    /**
     * @return State
     *   The source of this StateTransition.
     */
    public function src(): State;

    /**
     * @return State[]
     *   The destinations of this StateTransition.
     */
    public function dsts(): array;

    /**
     * @param State $s
     *   State to check whether it's a valid destination of this
     *   StateTransition.
     * @return bool
     *   True if `$s` is a destination of this StateTransition,
     *   False otherwise.
     */
    public function inDst(State $s): bool;
}
