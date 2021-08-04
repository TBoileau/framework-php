<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\ORM;

use TBoileau\Oc\Php\Project5\ORM\Mapping\Metadata;
use TBoileau\Oc\Php\Project5\ORM\Mapping\ResolverInterface;
use TBoileau\Oc\Php\Project5\Tests\KernelTestCase;
use TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Entity\Foo;
use TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Entity\Garply;
use TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Entity\Grault;
use TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Repository\FooRepository;

final class MappingResolverTest extends KernelTestCase
{
    /**
     * @test
     */
    public function ifResolveFooEntityIsSuccessful(): void
    {
        $kernel = static::bootKernel();

        /** @var ResolverInterface $resolver */
        $resolver = $kernel->getContainer()->get(ResolverInterface::class);

        $fooMetadata = $resolver->resolve(Foo::class);

        $this->assertInstanceOf(Metadata::class, $fooMetadata);
        $this->assertEquals(Foo::class, $fooMetadata->getClass()->getName());
        $this->assertEquals(FooRepository::class, $fooMetadata->getEntity()->repositoryClass);
        $this->assertEquals('foo_table', $fooMetadata->getTable()->name);

        $this->assertEquals('id', $fooMetadata->getPrimaryKey()->column->name);
        $this->assertEquals('integer', $fooMetadata->getPrimaryKey()->column->type);
        $this->assertTrue($fooMetadata->getPrimaryKey()->autoIncrement);

        $this->assertCount(6, $fooMetadata->getColumns());
        $this->assertCount(1, $fooMetadata->getManyToManys());
        $this->assertCount(1, $fooMetadata->getManyToOnes());
        $this->assertCount(0, $fooMetadata->getOneToManys());

        $this->assertEquals('bar_column', $fooMetadata->getColumn('bar')->name);
        $this->assertEquals('string', $fooMetadata->getColumn('bar')->type);
        $this->assertTrue($fooMetadata->getColumn('bar')->unique);
        $this->assertFalse($fooMetadata->getColumn('bar')->nullable);

        $this->assertEquals('baz', $fooMetadata->getColumn('baz')->name);
        $this->assertEquals('float', $fooMetadata->getColumn('baz')->type);
        $this->assertEquals(5, $fooMetadata->getColumn('baz')->precision);
        $this->assertEquals(4, $fooMetadata->getColumn('baz')->scale);
        $this->assertFalse($fooMetadata->getColumn('baz')->unique);
        $this->assertTrue($fooMetadata->getColumn('baz')->nullable);

        $this->assertEquals('qux', $fooMetadata->getColumn('qux')->name);
        $this->assertEquals('array', $fooMetadata->getColumn('qux')->type);
        $this->assertFalse($fooMetadata->getColumn('qux')->unique);
        $this->assertFalse($fooMetadata->getColumn('qux')->nullable);

        $this->assertEquals('quux', $fooMetadata->getColumn('quux')->name);
        $this->assertEquals('date', $fooMetadata->getColumn('quux')->type);
        $this->assertFalse($fooMetadata->getColumn('quux')->unique);
        $this->assertFalse($fooMetadata->getColumn('quux')->nullable);

        $this->assertEquals('corge', $fooMetadata->getColumn('corge')->name);
        $this->assertEquals('datetime', $fooMetadata->getColumn('corge')->type);
        $this->assertFalse($fooMetadata->getColumn('corge')->unique);
        $this->assertFalse($fooMetadata->getColumn('corge')->nullable);

        $this->assertEquals(Grault::class, $fooMetadata->getManyToOne('grault')->targetEntity);
        $this->assertEquals('foos', $fooMetadata->getManyToOne('grault')->inversedBy);
        $this->assertEquals('grault_id', $fooMetadata->getManyToOne('grault')->joinColumn);
        $this->assertTrue($fooMetadata->getManyToOne('grault')->nullable);

        $graultMetadata = $resolver->resolve($fooMetadata->getManyToOne('grault')->targetEntity);

        $this->assertCount(2, $graultMetadata->getColumns());
        $this->assertCount(0, $graultMetadata->getManyToManys());
        $this->assertCount(0, $graultMetadata->getManyToOnes());
        $this->assertCount(1, $graultMetadata->getOneToManys());

        $this->assertEquals(
            Foo::class,
            $graultMetadata->getOneToMany($fooMetadata->getManyToOne('grault')->inversedBy)->targetEntity
        );
        $this->assertEquals(
            'grault',
            $graultMetadata->getOneToMany($fooMetadata->getManyToOne('grault')->inversedBy)->mappedBy
        );

        $this->assertEquals(Garply::class, $fooMetadata->getManyToMany('garplies')->targetEntity);
        $this->assertEquals('foos', $fooMetadata->getManyToMany('garplies')->inversedBy);
        $this->assertEquals('foo_garplies', $fooMetadata->getManyToMany('garplies')->joinTable);

        $garplyMetadata = $resolver->resolve($fooMetadata->getManyToMany('garplies')->targetEntity);

        $this->assertCount(2, $garplyMetadata->getColumns());
        $this->assertCount(1, $garplyMetadata->getManyToManys());
        $this->assertCount(0, $garplyMetadata->getManyToOnes());
        $this->assertCount(0, $garplyMetadata->getOneToManys());

        $this->assertEquals(
            Foo::class,
            $garplyMetadata->getManyToMany($fooMetadata->getManyToMany('garplies')->inversedBy)->targetEntity
        );
        $this->assertEquals(
            'garplies',
            $garplyMetadata->getManyToMany($fooMetadata->getManyToMany('garplies')->inversedBy)->mappedBy
        );
    }
}
