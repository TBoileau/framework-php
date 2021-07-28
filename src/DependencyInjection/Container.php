<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\DependencyInjection;

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
     * @var array<string, string>
     */
    private array $parameters;

    /**
     * @var array<class-string, class-string>
     */
    private array $alias = [];

    public function __construct()
    {
        $this->services[$this::class] = $this;
    }

    /**
     * @throws ReflectionException
     */
    public function get(string $id): mixed
    {
        if (isset($this->alias[$id])) {
            return $this->get($this->alias[$id]);
        }
        if (isset($this->parameters[$id])) {
            return $this->parameters[$id];
        }
        if (!$this->has($id)) {
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
        return isset($this->services[$id]) || isset($this->parameters[$id]);
    }

    public function alias(string $alias, string $id): ContainerInterface
    {
        $this->alias[$alias] = $id;

        return $this;
    }

    public function setParameter(string $id, mixed $value): ContainerInterface
    {
        $this->parameters[$id] = $value;

        return $this;
    }
}
