<?php

declare(strict_types=1);

namespace Rc\StateMachine;

use DomainException;
use LengthException;

use function is_string;

final class StateTransition implements StateTransitionInterface
{
    /**
     * @param State[] $dsts
     */
    public function __construct(private State $src, private array $dsts)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function src(): State
    {
        return $this->src;
    }

    /**
     * {@inheritdoc}
     */
    public function dsts(): array
    {
        return $this->dsts;
    }

    /**
     * {@inheritdoc}
     */
    public function inDst(State $s): bool
    {
        return in_array($s, $this->dsts, true);
    }
}
