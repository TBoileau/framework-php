<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Router;

use Symfony\Component\HttpFoundation\Request;

final class Route
{
    private string $name;

    private string $path;

    private string $controller;

    private string $method;

    /**
     * @var array<string, string>
     */
    private array $requirements;

    /**
     * @var array<array-key, string>
     */
    private array $args = [];

    /**
     * @var array<string, string>
     */
    private array $matches = [];

    /**
     * @param array<string, string> $requirements
     */
    public static function create(
        string $name,
        string $path,
        string $controller,
        string $method,
        array $requirements = []
    ): Route {
        $route = new self();
        $route->name = $name;
        $route->path = $path;
        $route->controller = $controller;
        $route->method = $method;
        $route->requirements = $requirements;

        return $route;
    }

    public function match(Request $request): bool
    {
        $pattern = preg_replace_callback('/:(\w+)/', [$this, 'parameterMatch'], $this->path);

        $pattern = sprintf('#^%s$#', $pattern);

        if (!preg_match($pattern, $request->getRequestUri(), $this->matches)) {
            return false;
        }

        array_shift($this->matches);

        $this->matches = array_combine($this->args, $this->matches);

        return true;
    }

    /**
     * @param array<string, string> $parameters
     */
    public function parameterMatch(array $parameters): string
    {
        $parameter = $parameters[1];

        $this->args[] = $parameter;

        if (isset($this->requirements[$parameter])) {
            return sprintf('(%s)', $this->requirements[$parameter]);
        }

        return '([^/]+)';
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMatch(string $name): mixed
    {
        return $this->matches[$name];
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
