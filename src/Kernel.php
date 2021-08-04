<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5;

use Psr\Container\ContainerInterface as PsrContainer;
use Symfony\Component\HttpFoundation\Request;
use TBoileau\Oc\Php\Project5\Controller\AbstractController;
use TBoileau\Oc\Php\Project5\DependencyInjection\Container;
use TBoileau\Oc\Php\Project5\DependencyInjection\ContainerInterface;
use TBoileau\Oc\Php\Project5\Form\FormFactory;
use TBoileau\Oc\Php\Project5\Form\FormFactoryInterface;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Resolver;
use TBoileau\Oc\Php\Project5\ORM\Mapping\ResolverInterface;
use TBoileau\Oc\Php\Project5\PropertyAccess\PropertyAccessor;
use TBoileau\Oc\Php\Project5\PropertyAccess\PropertyAccessorInterface;
use TBoileau\Oc\Php\Project5\Router\Router;
use TBoileau\Oc\Php\Project5\Router\RouterInterface;
use TBoileau\Oc\Php\Project5\Templating\Templating;
use TBoileau\Oc\Php\Project5\Templating\TemplatingInterface;
use TBoileau\Oc\Php\Project5\Templating\TwigFactory;
use TBoileau\Oc\Php\Project5\Templating\TwigFactoryInterface;
use TBoileau\Oc\Php\Project5\Validator\ValidationConstraint\ValidationConstraintInterface;
use TBoileau\Oc\Php\Project5\Validator\Validator;
use TBoileau\Oc\Php\Project5\Validator\ValidatorInterface;
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
            ->setParameter('source_dir', __DIR__)
            ->setParameter('tests_dir', __DIR__.'/../tests')
            ->setParameter('vendor_dir', __DIR__.'/../vendor')
            ->setParameter('cache_dir', sprintf('%s/../var/cache/%s', __DIR__, $this->env))
            ->setParameter('templates_dirs', [__DIR__.'/../templates']);
    }

    public function configureServices(): void
    {
        $this->container
            ->bind('env', $this->container->get('env'))
            ->bind('cacheDir', $this->container->get('cache_dir'))
            ->bind('templatesDirs', $this->container->get('templates_dirs'))
            ->alias(RouterInterface::class, Router::class)
            ->alias(PsrContainer::class, Container::class)
            ->alias(ContainerInterface::class, Container::class)
            ->alias(TemplatingInterface::class, Templating::class)
            ->alias(TwigFactoryInterface::class, TwigFactory::class)
            ->alias(ValidatorInterface::class, Validator::class)
            ->alias(PropertyAccessorInterface::class, PropertyAccessor::class)
            ->alias(FormFactoryInterface::class, FormFactory::class)
            ->alias(ResolverInterface::class, Resolver::class)
            ->factory(Environment::class, TwigFactoryInterface::class)
            ->instanceOf(ValidationConstraintInterface::class, 'validator')
            ->resource('TBoileau\\Oc\\Php\\Project5\\Controller', 'controller', [AbstractController::class]);
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
