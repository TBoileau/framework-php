<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute;

use Attribute;

#[Attribute]
final class Column
{
    public const STRING = 'string';
    public const INTEGER = 'integer';
    public const FLOAT = 'float';
    public const ARRAY = 'array';
    public const DATE = 'date';
    public const DATETIME = 'datetime';

    public string $property;

    public function __construct(
        public ?string $name = null,
        public ?string $type = self::STRING,
        public ?bool $nullable = false,
        public ?bool $unique = false,
        public ?int $precision = null,
        public ?int $scale = null
    ) {
    }
}
