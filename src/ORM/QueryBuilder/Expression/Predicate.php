<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression;

use Stringable;

final class Predicate implements Stringable
{
    public function __construct(private string $predicate)
    {
    }

    public function __toString(): string
    {
        return $this->predicate;
    }

    public function getPredicate(): string
    {
        return $this->predicate;
    }
}
