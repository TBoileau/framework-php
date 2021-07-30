<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5;

use Psr\Container\ContainerInterface as PsrContainer;
use Symfony\Component\HttpFoundation\Request;
use TBoileau\Oc\Php\Project5\DependencyInjection\Container;
use TBoileau\Oc\Php\Project5\DependencyInjection\ContainerInterface;
use TBoileau\Oc\Php\Project5\Router\Router;
use TBoileau\Oc\Php\Project5\Router\RouterInterface;

final class Kernel
{
    private ContainerInterface $container;

    private Request $request;

    public function __construct(string $env, Request $request)
    {
        $this->request = $request;
        $this->container = new Container();
        $this->container->setParameter('env', $env);

        $this->configureServices();
        $this->configureRoutes();
    }

    public function configureServices(): void
    {
        $this->container->alias(RouterInterface::class, Router::class);
        $this->container->alias(PsrContainer::class, Container::class);
        $this->container->alias(ContainerInterface::class, Container::class);
    }

    public function configureRoutes(): void
    {
    }

    public function run(): void
    {
        /** @var RouterInterface $router */
        $router = $this->container->get(RouterInterface::class);

        $router->run($this->request)->send();
    }
}
