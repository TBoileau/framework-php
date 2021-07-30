<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\Templating;

use TBoileau\Oc\Php\Project5\Templating\TemplatingInterface;
use TBoileau\Oc\Php\Project5\Tests\KernelTestCase;

final class TemplatingTest extends KernelTestCase
{
    /**
     * @test
     */
    public function isTemplatingWorks(): void
    {
        $kernel = static::bootKernel();

        $templating = $kernel->getContainer()->get(TemplatingInterface::class);

        $this->assertInstanceOf(TemplatingInterface::class, $templating);
    }
}
