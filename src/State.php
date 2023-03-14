<?php

declare(strict_types=1);

namespace Halfowl\StateMachine;

use DomainException;
use LengthException;

use function is_string;

final class State implements StateInterface
{
    private string $name;

    /**
     * Create a new State.
     *
     * @param string $name
     *   Name of the State.
     * @throws DomainException
     *   If `$name` is not a string.
     * @throws LengthException
     *   If `$name` is empty.
     */
    public function __construct(string $name)
    {
        if (!is_string($name)) {
            throw new DomainException("Name must be a string");
        }
        if (strlen($name) === 0) {
            throw new LengthException("Name cannot be empty");
        }
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getName();
    }
}
