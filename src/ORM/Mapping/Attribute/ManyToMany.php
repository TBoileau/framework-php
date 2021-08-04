<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute;

use Attribute;

#[Attribute]
final class ManyToMany
{
    public string $property;

    public function __construct(
        public string $targetEntity,
        public ?string $mappedBy = null,
        public ?string $inversedBy = null,
        public ?string $joinTable = null
    ) {
    }
}
