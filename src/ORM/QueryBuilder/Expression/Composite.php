<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression;

use Stringable;

abstract class Composite implements Stringable
{
    /**
     * @var array<array-key, Predicate|Composite>
     */
    private array $parts = [];

    /**
     * @param array<array-key, Predicate|Composite> $parts
     */
    public function __construct(string | Predicate | Composite ...$parts)
    {
        $this->parts = array_map(
            static fn (string | Composite $part) => is_string($part) ? new Predicate($part) : $part,
            $parts
        );
    }

    public function __toString(): string
    {
        return sprintf('(%s)', implode(sprintf(' %s ', $this->getSeparator()), $this->parts));
    }

    /**
     * @return array<array-key, Predicate|Composite>
     */
    public function getParts(): array
    {
        return $this->parts;
    }

    abstract public function getSeparator(): string;
}
