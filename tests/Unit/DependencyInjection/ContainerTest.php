<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use TBoileau\Oc\Php\Project5\DependencyInjection\Container;
use TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\BarService;
use TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\FooService;

final class ContainerTest extends TestCase
{
    /**
     * @test
     */
    public function getService(): void
    {
        $container = new Container();

        $foo = $container->get(FooService::class);

        $this->assertInstanceOf(FooService::class, $foo);
        $this->assertInstanceOf(BarService::class, $foo->bar);
    }
}
