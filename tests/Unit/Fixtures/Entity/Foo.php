<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Entity;

use TBoileau\Oc\Php\Project5\Validator\Constraint\NotBlank;

class Foo
{
    #[NotBlank(message: 'Bar ne doit pas être vide.')]
    private string $bar;

    private bool $valid;

    private bool $AtLeast18YearsOld;

    public string $baz;

    private string $qux = 'qux';

    public function getBar(): string
    {
        return $this->bar;
    }

    public function setBar(string $bar): void
    {
        $this->bar = $bar;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): void
    {
        $this->valid = $valid;
    }

    public function hasAtLeast18YearsOld(): bool
    {
        return $this->AtLeast18YearsOld;
    }

    public function setAtLeast18YearsOld(bool $AtLeast18YearsOld): void
    {
        $this->AtLeast18YearsOld = $AtLeast18YearsOld;
    }
}
