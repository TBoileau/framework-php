<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression;

final class Join
{
    public function __construct(private string $relation, private string $alias)
    {
    }

    public function __toString(): string
    {
        return sprintf('%s AS %s', $this->relation, $this->alias);
    }

    public function getRelation(): string
    {
        return $this->relation;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }
}
