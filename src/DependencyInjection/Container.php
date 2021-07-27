<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\DependencyInjection;

use League\Container\Exception\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

final class Container implements ContainerInterface
{
    /**
     * @var array<class-string, object>
     */
    private array $services;

    /**
     * @throws ReflectionException
     */
    public function get(string $id): object
    {
        if (!$this->has($id)) {
            if (!class_exists($id)) {
                throw new NotFoundException(sprintf('Class %s doesn\'t exist.', $id));
            }

            $reflectionClass = new ReflectionClass($id);

            $constructorArgs = [];

            if (null !== $reflectionClass->getConstructor()) {
                $constructorArgs = array_map(
                    fn (ReflectionParameter $parameter) => $this->get((string) $parameter->getType()),
                    $reflectionClass->getConstructor()->getParameters()
                );
            }

            $this->services[$id] = $reflectionClass->newInstanceArgs($constructorArgs);
        }

        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }
}
