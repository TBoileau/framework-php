<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\Validator;

use TBoileau\Oc\Php\Project5\Tests\KernelTestCase;
use TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Entity\Foo;
use TBoileau\Oc\Php\Project5\Validator\ValidatorInterface;

final class ValidatorTest extends KernelTestCase
{
    /**
     * @test
     */
    public function ifValidatorWorks(): void
    {
        $kernel = static::bootKernel();

        $foo = new Foo();
        $foo->setBar('');

        /** @var ValidatorInterface $validator */
        $validator = $kernel->getContainer()->get(ValidatorInterface::class);

        $this->assertCount(1, $validator->validate($foo));
    }
}
