<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Controller;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TBoileau\Oc\Php\Project5\DependencyInjection\ServicesSubscriberInterface;
use TBoileau\Oc\Php\Project5\Form\FormFactoryInterface;
use TBoileau\Oc\Php\Project5\Router\RouterInterface;
use TBoileau\Oc\Php\Project5\Templating\TemplatingInterface;

abstract class AbstractController implements ServicesSubscriberInterface
{
    private ContainerInterface $container;

    public function handleForm(string $formName, object $data, Request $request, callable $callable): bool
    {
        /** @var FormFactoryInterface $formFactory */
        $formFactory = $this->container->get(FormFactoryInterface::class);

        $form = $formFactory->create($formName, $data)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            call_user_func($callable, [$data]);

            return true;
        }

        return false;
    }

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
            FormFactoryInterface::class,
        ];
    }
}
