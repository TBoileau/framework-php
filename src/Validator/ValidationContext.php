<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Validator;

final class ValidationContext
{
    public function __construct(private ConstraintViolationList $constraintViolationList, private string $path)
    {
    }

    public function buildViolation(string $message): void
    {
        $this->constraintViolationList->add(new ConstraintViolation($this->path, $message));
    }
}
