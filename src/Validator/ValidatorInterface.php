<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Validator;

interface ValidatorInterface
{
    public function validate(object $context): ConstraintViolationList;
}
