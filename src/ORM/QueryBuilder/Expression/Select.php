<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression;

use Stringable;

final class Select implements Stringable
{
    public function __construct(private string $property, private ?string $alias = null)
    {
    }

    public function __toString(): string
    {
        if (null !== $this->alias) {
            return sprintf('%s AS %s', $this->property, $this->alias);
        }

        return $this->property;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }
}
