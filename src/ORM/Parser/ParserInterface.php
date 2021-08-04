<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\Parser;

use TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Query;

interface ParserInterface
{
    public function parse(Query $query): string;
}
