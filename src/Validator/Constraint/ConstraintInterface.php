<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Validator\Constraint;

interface ConstraintInterface
{
    public static function getValidationConstraint(): string;
}
