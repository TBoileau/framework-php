<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression;

use Stringable;

final class GroupBy implements Stringable
{
    public function __construct(private string $property)
    {
    }

    public function __toString(): string
    {
        return $this->property;
    }

    public function getProperty(): string
    {
        return $this->property;
    }
}
