<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\PropertyAccess;

use ReflectionClass;
use ReflectionException;
use function Symfony\Component\String\u;
use TBoileau\Oc\Php\Project5\PropertyAccess\Exception\GetterNotFound;
use TBoileau\Oc\Php\Project5\PropertyAccess\Exception\SetterNotFound;

final class PropertyAccessor implements PropertyAccessorInterface
{
    /**
     * @throws ReflectionException
     */
    public function getValue(object $object, string $propertyPath): mixed
    {
        $reflectionClass = new ReflectionClass($object);
        $getter = sprintf('get%s', u($propertyPath)->camel()->title()->toString());
        if ($reflectionClass->hasMethod($getter)) {
            return $reflectionClass->getMethod($getter)->invoke($object);
        }
        $isser = sprintf('is%s', u($propertyPath)->camel()->title()->toString());
        if ($reflectionClass->hasMethod($isser)) {
            return $reflectionClass->getMethod($isser)->invoke($object);
        }
        $hasser = sprintf('has%s', u($propertyPath)->camel()->title()->toString());
        if ($reflectionClass->hasMethod($hasser)) {
            return $reflectionClass->getMethod($hasser)->invoke($object);
        }
        if ($reflectionClass->getProperty($propertyPath)->isPublic()) {
            return $reflectionClass->getProperty($propertyPath)->getValue($object);
        }
        throw new GetterNotFound(sprintf('No getter is implemented for %s', $propertyPath));
    }

    /**
     * @throws ReflectionException
     */
    public function setValue(object $object, string $propertyPath, mixed $value): void
    {
        $reflectionClass = new ReflectionClass($object);
        $setter = sprintf('set%s', u($propertyPath)->camel()->title()->toString());
        if ($reflectionClass->hasMethod($setter)) {
            $reflectionClass->getMethod($setter)->invokeArgs($object, [$value]);

            return;
        }
        if ($reflectionClass->getProperty($propertyPath)->isPublic()) {
            $reflectionClass->getProperty($propertyPath)->setValue($object, $value);

            return;
        }
        throw new SetterNotFound(sprintf('No setter is implemented for %s', $propertyPath));
    }
}
