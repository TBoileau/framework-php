<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use TBoileau\Oc\Php\Project5\DependencyInjection\Container;

final class Kernel
{
    private ContainerInterface $container;

    private Request $request;

    private string $env;

    public function __construct(string $env, Request $request)
    {
        $this->env = $env;
        $this->request = $request;
        $this->container = new Container();
    }

    public function run(): void
    {
    }
}
