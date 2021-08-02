<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Form;

use Symfony\Component\HttpFoundation\Request;
use TBoileau\Oc\Php\Project5\Validator\ConstraintViolationList;

interface FormInterface
{
    public function handleRequest(Request $request): FormInterface;

    public function isSubmitted(): bool;

    public function isValid(): bool;

    public function getErrors(string $field): ConstraintViolationList;
}
