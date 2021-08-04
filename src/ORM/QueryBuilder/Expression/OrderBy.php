<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression;

use Stringable;

final class OrderBy implements Stringable
{
    public function __construct(private string $property, private string $direction)
    {
    }

    public function __toString(): string
    {
        return sprintf('%s %s', $this->property, $this->direction);
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }
}
