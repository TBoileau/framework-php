<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression;

final class OrX extends Composite
{
    public function getSeparator(): string
    {
        return 'OR';
    }
}
