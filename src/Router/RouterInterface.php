<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Router;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RouterInterface extends UrlGeneratorInterface
{
    public function add(Route $route): RouterInterface;

    public function run(Request $request): Response;
}
