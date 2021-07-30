<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\DependencyInjection;

use Psr\Container\ContainerInterface as BaseContainer;

interface ContainerInterface extends BaseContainer
{
    public function alias(string $alias, string $id): ContainerInterface;

    public function bind(string $key, mixed $value): ContainerInterface;

    public function setParameter(string $id, mixed $value): ContainerInterface;

    public function register(string $id, mixed $value): ContainerInterface;

    public function factory(string $id, string $factory, string $method = 'create'): ContainerInterface;

    /**
     * @param array<array-key, class-string> $excludes
     */
    public function resource(string $namespace, string $tag, array $excludes = []): ContainerInterface;

    public function instanceOf(string $interface, string $tag): ContainerInterface;
}
