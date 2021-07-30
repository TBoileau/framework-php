<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Templating;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class TwigFactory implements TwigFactoryInterface
{
    /**
     * @param array<array-key, string> $templatesDirs
     */
    public function __construct(private string $cacheDir, private array $templatesDirs)
    {
    }

    public function create(): Environment
    {
        $loader = new FilesystemLoader($this->templatesDirs);

        return new Environment($loader, [
            'cache' => sprintf('%s/twig', $this->cacheDir),
        ]);
    }
}
