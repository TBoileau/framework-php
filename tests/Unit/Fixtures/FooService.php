<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures;

class FooService
{
    public function __construct(public BarService $bar)
    {
    }
}
