<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute;

use Attribute;

#[Attribute]
final class Entity
{
    public function __construct(public ?string $repositoryClass = null)
    {
    }
}
