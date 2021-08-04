<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\Form;

use Symfony\Component\HttpFoundation\Request;
use TBoileau\Oc\Php\Project5\Form\FormFactoryInterface;
use TBoileau\Oc\Php\Project5\Tests\KernelTestCase;
use TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Model\Foo;

final class FormTest extends KernelTestCase
{
    /**
     * @test
     */
    public function ifFormIsValid(): void
    {
        $kernel = static::bootKernel();

        /** @var FormFactoryInterface $formFactory */
        $formFactory = $kernel->getContainer()->get(FormFactoryInterface::class);

        $foo = new Foo();
        $foo->setBar('');

        $form = $formFactory
            ->create('foo', $foo)
            ->handleRequest(Request::create('/', Request::METHOD_POST, ['foo' => ['bar' => 'test']]));

        $this->assertTrue($form->isSubmitted());
        $this->assertTrue($form->isValid());
        $this->assertEquals('test', $foo->getBar());
    }

    /**
     * @test
     */
    public function ifFormIsInvalid(): void
    {
        $kernel = static::bootKernel();

        /** @var FormFactoryInterface $formFactory */
        $formFactory = $kernel->getContainer()->get(FormFactoryInterface::class);

        $foo = new Foo();
        $foo->setBar('');

        $form = $formFactory
            ->create('foo', $foo)
            ->handleRequest(Request::create('/', Request::METHOD_POST, ['foo' => ['bar' => '']]));

        $this->assertTrue($form->isSubmitted());
        $this->assertFalse($form->isValid());
        $this->assertEquals('', $foo->getBar());
        $this->assertCount(1, $form->getErrors('bar'));
    }
}
