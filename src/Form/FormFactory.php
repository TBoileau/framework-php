<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Form;

use TBoileau\Oc\Php\Project5\PropertyAccess\PropertyAccessorInterface;
use TBoileau\Oc\Php\Project5\Validator\ValidatorInterface;

final class FormFactory implements FormFactoryInterface
{
    public function __construct(
        private ValidatorInterface $validator,
        private PropertyAccessorInterface $propertyAccessor
    ) {
    }

    public function create(string $name, object $data): FormInterface
    {
        return new Form($this->propertyAccessor, $this->validator, $name, $data);
    }
}
