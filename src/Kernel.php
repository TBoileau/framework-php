<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5;

use Psr\Container\ContainerInterface as PsrContainer;
use Symfony\Component\HttpFoundation\Request;
use TBoileau\Oc\Php\Project5\DependencyInjection\Container;
use TBoileau\Oc\Php\Project5\DependencyInjection\ContainerInterface;
use TBoileau\Oc\Php\Project5\Router\Router;
use TBoileau\Oc\Php\Project5\Router\RouterInterface;
use TBoileau\Oc\Php\Project5\Templating\Templating;
use TBoileau\Oc\Php\Project5\Templating\TemplatingInterface;
use TBoileau\Oc\Php\Project5\Templating\TwigFactory;
use TBoileau\Oc\Php\Project5\Templating\TwigFactoryInterface;
use Twig\Environment;

final class Kernel
{
    private ContainerInterface $container;

    private string $env;

    public function __construct(string $env)
    {
        $this->env = $env;
        $this->container = new Container();

        $this->configureParameters();
        $this->configureServices();
        $this->configureRoutes();
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function configureParameters(): void
    {
        $this->container
            ->setParameter('env', $this->env)
            ->setParameter('cache_dir', sprintf('%s/../var/cache/%s', __DIR__, $this->env))
            ->setParameter('templates_dir', __DIR__ . '/../templates');
    }

    public function configureServices(): void
    {
        $this->container
            ->bind('cacheDir', $this->container->get('cache_dir'))
            ->bind('templatesDir', $this->container->get('templates_dir'))
            ->alias(RouterInterface::class, Router::class)
            ->alias(PsrContainer::class, Container::class)
            ->alias(ContainerInterface::class, Container::class)
            ->alias(TemplatingInterface::class, Templating::class)
            ->alias(TwigFactoryInterface::class, TwigFactory::class)
            ->factory(Environment::class, TwigFactoryInterface::class);
    }

    public function configureRoutes(): void
    {
    }

    public function run(Request $request): void
    {
        /** @var RouterInterface $router */
        $router = $this->container->get(RouterInterface::class);

        $router->run($request)->send();
    }
}
