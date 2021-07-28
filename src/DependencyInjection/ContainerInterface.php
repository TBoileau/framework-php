<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\DependencyInjection;

use Psr\Container\ContainerInterface as BaseContainer;

interface ContainerInterface extends BaseContainer
{
    public function alias(string $alias, string $id): ContainerInterface;

    public function setParameter(string $id, mixed $value): ContainerInterface;
}
