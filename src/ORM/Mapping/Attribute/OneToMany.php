<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute;

use Attribute;

#[Attribute]
final class OneToMany
{
    public string $property;

    public function __construct(public string $targetEntity, public ?string $mappedBy = null)
    {
    }
}
