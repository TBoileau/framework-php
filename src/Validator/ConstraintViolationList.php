<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Validator;

use Countable;
use Iterator;

final class ConstraintViolationList implements Countable, Iterator
{
    /**
     * @var array<array-key, ConstraintViolation>
     */
    private array $constraintViolations = [];

    private int $position;

    public function add(ConstraintViolation $constraintViolation): void
    {
        $this->constraintViolations[] = $constraintViolation;
    }

    public function current(): ConstraintViolation
    {
        return $this->constraintViolations[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->constraintViolations[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function count(): int
    {
        return count($this->constraintViolations);
    }
}
