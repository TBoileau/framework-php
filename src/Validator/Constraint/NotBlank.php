<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Validator\Constraint;

use Attribute;
use TBoileau\Oc\Php\Project5\Validator\ValidationConstraint\NotBlankValidator;

#[Attribute]
final class NotBlank implements ConstraintInterface
{
    public function __construct(public string $message = 'This value should not be blank.')
    {
    }

    public static function getValidationConstraint(): string
    {
        return NotBlankValidator::class;
    }
}
