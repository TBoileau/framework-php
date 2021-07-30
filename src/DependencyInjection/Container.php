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

    /**
     * @var array<class-string, array<string, class-string|string>>
     */
    private array $factories = [];

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
            if (isset($this->factories[$id])) {
                $factory = $this->get($this->factories[$id]["class"]);

                $this->services[$id] = call_user_func([$factory, $this->factories[$id]["method"]]);
            } else {
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

    public function factory(string $id, string $factory, string $method = 'create'): ContainerInterface
    {
        $this->factories[$id] = ['class' => $factory, 'method' => $method];

        return $this;
    }

    public function setParameter(string $id, mixed $value): ContainerInterface
    {
        $this->parameters[$id] = $value;

        return $this;
    }
}
