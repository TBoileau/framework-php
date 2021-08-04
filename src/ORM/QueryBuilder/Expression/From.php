<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression;

use Stringable;

final class From implements Stringable
{
    /**
     * @param class-string $entity
     */
    public function __construct(private string $entity, private string $alias)
    {
    }

    public function __toString(): string
    {
        return sprintf('%s AS %s', $this->entity, $this->alias);
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }
}
