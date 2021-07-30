<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Controller;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use TBoileau\Oc\Php\Project5\DependencyInjection\ServiceSubscriberInterface;
use TBoileau\Oc\Php\Project5\Router\RouterInterface;
use TBoileau\Oc\Php\Project5\Templating\TemplatingInterface;

abstract class AbstractController implements ServiceSubscriberInterface
{
    private ContainerInterface $container;

    /**
     * @param array<string, mixed> $context
     */
    public function render(string $view, array $context = []): Response
    {
        /** @var TemplatingInterface $templating */
        $templating = $this->container->get(TemplatingInterface::class);

        return new Response($templating->render($view, $context));
    }

    /**
     * @param array<string, mixed> $context
     */
    public function redirect(string $route, array $context = []): RedirectResponse
    {
        /** @var RouterInterface $router */
        $router = $this->container->get(RouterInterface::class);

        return new RedirectResponse($router->generateUrl($route, $context));
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    /**
     * @return array<array-key, class-string>
     */
    public static function getSubscribedServices(): array
    {
        return [
            RouterInterface::class,
            TemplatingInterface::class,
        ];
    }
}
