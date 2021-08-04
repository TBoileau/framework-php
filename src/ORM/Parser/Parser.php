<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\Parser;

use TBoileau\Oc\Php\Project5\ORM\Mapping\ResolverInterface;
use TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Query;

final class Parser implements ParserInterface
{
    public function __construct(private ResolverInterface $resolver)
    {
    }

    public function parse(Query $query): string
    {
        return $query->getDql();
    }
}
