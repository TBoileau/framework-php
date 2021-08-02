<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Form;

use Symfony\Component\HttpFoundation\Request;
use TBoileau\Oc\Php\Project5\PropertyAccess\PropertyAccessorInterface;
use TBoileau\Oc\Php\Project5\Validator\ConstraintViolation;
use TBoileau\Oc\Php\Project5\Validator\ConstraintViolationList;
use TBoileau\Oc\Php\Project5\Validator\ValidatorInterface;

final class Form implements FormInterface
{
    private ?Request $request = null;

    private ?ConstraintViolationList $constraintViolationList;

    public function __construct(
        private PropertyAccessorInterface $propertyAccessor,
        private ValidatorInterface $validator,
        private string $name,
        private mixed $data
    ) {
    }

    public function handleRequest(Request $request): FormInterface
    {
        $this->request = $request;

        if ($this->isSubmitted()) {
            $data = $this->request->request->get($this->name);

            foreach ($data as $k => $v) {
                $this->propertyAccessor->setValue($this->data, $k, $v);
            }

            $this->constraintViolationList = $this->validator->validate($this->data);
        }

        return $this;
    }

    public function isSubmitted(): bool
    {
        return null !== $this->request && $this->request->isMethod(Request::METHOD_POST);
    }

    public function isValid(): bool
    {
        return 0 === count($this->constraintViolationList);
    }

    public function getErrors(string $field): ConstraintViolationList
    {
        return $this->constraintViolationList->filter(
            static fn (ConstraintViolation $constraintViolation) => $constraintViolation->getPath() === $field
        );
    }
}
