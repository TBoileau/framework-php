<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Validator\ValidationConstraint;

use Assert\Assertion;
use Assert\AssertionFailedException;
use TBoileau\Oc\Php\Project5\Validator\Constraint\ConstraintInterface;
use TBoileau\Oc\Php\Project5\Validator\ValidationContext;

final class NotBlankValidator implements ValidationConstraintInterface
{
    public function validate(mixed $value, ConstraintInterface $constraint, ValidationContext $context): void
    {
        try {
            Assertion::notBlank($value);
        } catch (AssertionFailedException) {
            $context->buildViolation($constraint->message);
        }
    }
}
