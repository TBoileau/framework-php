<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute;

use Attribute;

#[Attribute]
final class PrimaryKey
{
    public string $property;

    public Column $column;

    public function __construct(public bool $autoIncrement = true)
    {
    }
}
