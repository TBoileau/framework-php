<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Entity;

use TBoileau\Oc\Php\Project5\Validator\Constraint\NotBlank;

class Foo
{
    #[NotBlank(message: '{{ label }} ne doit pas être vide.')]
    private string $bar;

    public function getBar(): string
    {
        return $this->bar;
    }

    public function setBar(string $bar): void
    {
        $this->bar = $bar;
    }
}
