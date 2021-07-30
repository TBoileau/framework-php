<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Router;

interface UrlGeneratorInterface
{
    /**
     * @param array<string, mixed> $context
     */
    public function generateUrl(string $route, array $context = []): string;
}
