<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\PropertyAccess;

interface PropertyAccessorInterface
{
    public function getValue(object $object, string $propertyPath): mixed;

    public function setValue(object $object, string $propertyPath, mixed $value): void;
}
