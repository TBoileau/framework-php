<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\PropertyAccess;

use TBoileau\Oc\Php\Project5\PropertyAccess\Exception\GetterNotFound;
use TBoileau\Oc\Php\Project5\PropertyAccess\Exception\SetterNotFound;
use TBoileau\Oc\Php\Project5\PropertyAccess\PropertyAccessorInterface;
use TBoileau\Oc\Php\Project5\Tests\KernelTestCase;
use TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Model\Foo;

final class PropertyAccessorTest extends KernelTestCase
{
    private PropertyAccessorInterface $propertyAccessor;

    protected function setUp(): void
    {
        $kernel = static::bootKernel();
        $this->propertyAccessor = $kernel->getContainer()->get(PropertyAccessorInterface::class);
    }

    /**
     * @test
     */
    public function ifPropertyAccessorIsSuccessful(): void
    {
        $foo = new Foo();

        $this->propertyAccessor->setValue($foo, 'baz', 'baz');
        $this->assertEquals('baz', $this->propertyAccessor->getValue($foo, 'baz'));

        $this->propertyAccessor->setValue($foo, 'bar', 'bar');
        $this->assertEquals('bar', $this->propertyAccessor->getValue($foo, 'bar'));

        $this->propertyAccessor->setValue($foo, 'valid', true);
        $this->assertTrue($this->propertyAccessor->getValue($foo, 'valid'));

        $this->propertyAccessor->setValue($foo, 'atLeast18YearsOld', true);
        $this->assertTrue($this->propertyAccessor->getValue($foo, 'atLeast18YearsOld'));
    }

    /**
     * @test
     */
    public function ifNoSetterIsImplemented(): void
    {
        $foo = new Foo();

        $this->expectException(SetterNotFound::class);

        $this->propertyAccessor->setValue($foo, 'qux', 'qux');
    }

    /**
     * @test
     */
    public function ifNoGetterIsImplemented(): void
    {
        $foo = new Foo();

        $this->expectException(GetterNotFound::class);

        $this->assertEquals('qux', $this->propertyAccessor->getValue($foo, 'qux'));
    }
}
