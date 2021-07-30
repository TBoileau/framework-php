<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\DependencyInjection;

use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

final class Container implements ContainerInterface
{
    /**
     * @var array<class-string, mixed>
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
     * @var array<string, mixed>
     */
    private array $bind = [];

    /**
     * @var array<class-string, array<string, class-string|string>>
     */
    private array $factories = [];

    /**
     * @var array<string, array<array-key, class-string>>
     */
    private array $tags = [];

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
                $factory = $this->get($this->factories[$id]['class']);

                $this->services[$id] = call_user_func([$factory, $this->factories[$id]['method']]);
            } else {
                $reflectionClass = new ReflectionClass($id);
                $constructorArgs = [];
                if (null !== $reflectionClass->getConstructor()) {
                    $constructorArgs = array_map(
                        [$this, 'getService'],
                        $reflectionClass->getConstructor()->getParameters()
                    );
                }

                /** @var ServicesSubscriberInterface|TaggedServicesInterface $service */
                $service = $reflectionClass->newInstanceArgs($constructorArgs);

                $this->register($id, $service);

                if ($reflectionClass->implementsInterface(ServicesSubscriberInterface::class)) {
                    $serviceLocator = new Container();
                    foreach ($service::getSubscribedServices() as $subscribedService) {
                        $serviceLocator->register($subscribedService, $this->get($subscribedService));
                    }

                    $service->setContainer($serviceLocator);
                }

                if ($reflectionClass->implementsInterface(TaggedServicesInterface::class)) {
                    $serviceLocator = new Container();
                    foreach ($service::getTags() as $tag) {
                        foreach ($this->tags[$tag] ?? [] as $services) {
                            $serviceLocator->register($services, $this->get($services));
                        }
                    }

                    $service->setContainer($serviceLocator);
                }
            }
        }

        return $this->services[$id];
    }

    public function getService(ReflectionParameter $parameter): mixed
    {
        if (isset($this->bind[$parameter->getName()])) {
            return $this->bind[$parameter->getName()];
        }

        return $this->get((string) $parameter->getType());
    }

    public function bind(string $key, mixed $value): ContainerInterface
    {
        $this->bind[$key] = $value;

        return $this;
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

    public function register(string $id, mixed $value): ContainerInterface
    {
        $this->services[$id] = $value;

        return $this;
    }

    public function instanceOf(string $interface, string $tag): ContainerInterface
    {
        $classes = get_declared_classes();

        foreach ($classes as $class) {
            $reflectionClass = new ReflectionClass($class);
            if ($reflectionClass->implementsInterface($interface)) {
                $this->tags[$tag][] = $reflectionClass->getName();
            }
        }

        return $this;
    }
}
