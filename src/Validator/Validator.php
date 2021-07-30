<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Validator;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use TBoileau\Oc\Php\Project5\DependencyInjection\TaggedServicesInterface;
use TBoileau\Oc\Php\Project5\Validator\Constraint\ConstraintInterface;
use TBoileau\Oc\Php\Project5\Validator\ValidationConstraint\ValidationConstraintInterface;

final class Validator implements ValidatorInterface, TaggedServicesInterface
{
    private ContainerInterface $container;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    /**
     * @return ConstraintViolationList<ConstraintViolation>
     *
     * @throws \ReflectionException
     */
    public function validate(object $object): ConstraintViolationList
    {
        $constraintViolationList = new ConstraintViolationList();

        $reflectionClass = new ReflectionClass($object);

        foreach ($reflectionClass->getProperties() as $property) {
            foreach ($property->getAttributes() as $attribute) {
                $reflectionClass = new ReflectionClass($attribute->getName());
                if ($reflectionClass->implementsInterface(ConstraintInterface::class)) {
                    $property->setAccessible(true);
                    $value = $property->getValue($object);
                    /** @var ConstraintInterface $constraint */
                    $constraint = $reflectionClass->newInstance();
                    $this->validateChild(
                        new ValidationContext(
                            $constraintViolationList,
                            $property->getName()
                        ),
                        $constraint,
                        $value
                    );
                }
            }
        }

        return $constraintViolationList;
    }

    /**
     * @return array<array-key, string>
     */
    public static function getTags(): array
    {
        return ['validator'];
    }

    private function validateChild(ValidationContext $context, ConstraintInterface $constraint, mixed $value): void
    {
        $validationConstraintClass = $constraint::getValidationConstraint();

        /** @var ValidationConstraintInterface $validationConstraint */
        $validationConstraint = $this->container->get($validationConstraintClass);
        $validationConstraint->validate($value, $constraint, $context);
    }
}
