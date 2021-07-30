<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Validator\ValidationConstraint;

use TBoileau\Oc\Php\Project5\Validator\Constraint\ConstraintInterface;
use TBoileau\Oc\Php\Project5\Validator\ValidationContext;

interface ValidationConstraintInterface
{
    public function validate(mixed $value, ConstraintInterface $constraint, ValidationContext $context): void;
}
