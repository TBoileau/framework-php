<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Templating;

use Twig\Environment;

interface TwigFactoryInterface
{
    public function create(): Environment;
}
