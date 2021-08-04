<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute;

use Attribute;

#[Attribute]
final class ManyToOne
{
    public string $property;

    public function __construct(
        public string $targetEntity,
        public bool $nullable = true,
        public ?string $inversedBy = null,
        public ?string $joinColumn = null
    ) {
    }
}
