<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Validator;

use ArrayAccess;
use Countable;
use Iterator;

/**
 * @template ConstraintViolation
 *
 * @extends ConstraintViolationList<ConstraintViolation>
 */
final class ConstraintViolationList implements Countable, Iterator, ArrayAccess
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

    /**
     * @param int $offset
     *
     * @codeCoverageIgnore
     */
    public function offsetExists($offset): bool
    {
        return isset($this->constraintViolations[$offset]);
    }

    /**
     * @param int $offset
     *
     * @codeCoverageIgnore
     */
    public function offsetGet($offset): ConstraintViolation
    {
        return $this->constraintViolations[$offset];
    }

    /**
     * @codeCoverageIgnore
     *
     * @param int   $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->constraintViolations[$offset] = $value;
    }

    /**
     * @param int $offset
     *
     * @codeCoverageIgnore
     */
    public function offsetUnset($offset): void
    {
        unset($this->constraintViolations[$offset]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function current(): ConstraintViolation
    {
        return $this->constraintViolations[$this->position];
    }

    /**
     * @codeCoverageIgnore
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * @codeCoverageIgnore
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @codeCoverageIgnore
     */
    public function valid(): bool
    {
        return isset($this->constraintViolations[$this->position]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @codeCoverageIgnore
     */
    public function count(): int
    {
        return count($this->constraintViolations);
    }
}
