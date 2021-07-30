<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Templating;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

final class Templating implements TemplatingInterface
{
    public function __construct(private Environment $twig)
    {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     *
     * @param array<string, mixed> $context
     */
    public function render(string $view, array $context = []): string
    {
        return $this->twig->render($view, $context);
    }
}
