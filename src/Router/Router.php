<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Router;

use Psr\Container\ContainerInterface;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TBoileau\Oc\Php\Project5\DependencyInjection\TaggedServicesInterface;
use TBoileau\Oc\Php\Project5\Router\Exception\RouteNotFound;

final class Router implements RouterInterface, TaggedServicesInterface
{
    /**
     * @var array<string, Route>
     */
    private array $routes = [];

    private ContainerInterface $container;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function add(Route $route): RouterInterface
    {
        $this->routes[$route->getName()] = $route;

        return $this;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function generateUrl(string $route, array $context = []): string
    {
        if (!isset($this->routes[$route])) {
            throw new RouteNotFound(sprintf('Route %s not found.', $route));
        }

        $route = $this->routes[$route];

        return $route->generateUrl($context);
    }

    /**
     * @throws \ReflectionException
     */
    public function run(Request $request): Response
    {
        foreach ($this->routes as $route) {
            if ($route->match($request)) {
                $reflectionMethod = new ReflectionMethod($route->getController(), $route->getMethod());

                $args = [];

                foreach ($reflectionMethod->getParameters() as $parameter) {
                    if (Request::class === (string) $parameter->getType()) {
                        $args[] = $request;
                    } else {
                        $args[] = $route->getMatch($parameter->getName());
                    }
                }

                return $reflectionMethod->invokeArgs($this->container->get($route->getController()), $args);
            }
        }

        throw new RouteNotFound(sprintf('No route found for %s.', $request->getRequestUri()));
    }

    /**
     * @return array<array-key, string>
     */
    public static function getTaggedServices(): array
    {
        return ['controller'];
    }
}
