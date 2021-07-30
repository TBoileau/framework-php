<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests;

use PHPUnit\Framework\TestCase;
use TBoileau\Oc\Php\Project5\Kernel;

abstract class KernelTestCase extends TestCase
{
    public static function bootKernel(): Kernel
    {
        $kernel = new Kernel($_ENV['APP_ENV']);
        $kernel->getContainer()->bind('templatesDirs', [
            __DIR__.'/../templates',
            __DIR__.'/Unit/Fixtures/templates',
        ]);
        $kernel->getContainer()->resource(
            'TBoileau\\Oc\\Php\\Project5\\Tests\\Unit\\Fixtures\\Controller',
            'controller',
        );

        return $kernel;
    }
}
