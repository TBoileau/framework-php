<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Templating;

interface TemplatingInterface
{
    /**
     * @param array<string, mixed> $context
     */
    public function render(string $view, array $context = []): string;
}
