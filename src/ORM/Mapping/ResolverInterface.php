<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\Mapping;

interface ResolverInterface
{
    public function resolve(string | object $entity): Metadata;
}
