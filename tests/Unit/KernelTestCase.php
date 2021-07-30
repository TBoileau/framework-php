<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TBoileau\Oc\Php\Project5\Kernel;

abstract class KernelTestCase extends TestCase
{
    public static function bootKernel(): Kernel
    {
        return new Kernel($_ENV['APP_ENV']);
    }
}
