<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\Mapping;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use function Symfony\Component\String\u;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\Column;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\Entity;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\ManyToMany;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\ManyToOne;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\OneToMany;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\PrimaryKey;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\Table;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Exception\FinalClass;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Exception\MappingExists;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Exception\MappingNotFound;

final class Resolver implements ResolverInterface
{
    /**
     * @throws MappingNotFound
     * @throws ReflectionException
     * @throws MappingExists
     */
    public function resolve(object | string $entity): Metadata
    {
        $reflectionClass = new ReflectionClass($entity);

        if ($reflectionClass->isFinal()) {
            throw new FinalClass(sprintf('CLass %s cannot be final.', $reflectionClass->getName()));
        }

        $metadata = Metadata::create($reflectionClass);

        if (0 === count($reflectionClass->getAttributes(Entity::class))) {
            throw new MappingNotFound(sprintf('Entity mapping not found for class %s.', $reflectionClass->getName()));
        }

        $entityAttribute = $reflectionClass->getAttributes(Entity::class)[0];

        /** @var Entity $entity */
        $entity = (new ReflectionClass($entityAttribute->getName()))->newInstance(...$entityAttribute->getArguments());

        $metadata->setEntity($entity);

        if (0 === count($reflectionClass->getAttributes(Table::class))) {
            $metadata->setTable(new Table(u($reflectionClass->getShortName())->lower()->snake()->toString()));
        } else {
            $tableAttribute = $reflectionClass->getAttributes(Table::class)[0];

            /** @var Table $table */
            $table = (new ReflectionClass($tableAttribute->getName()))->newInstance(...$tableAttribute->getArguments());

            $metadata->setTable($table);
        }

        foreach ($reflectionClass->getProperties() as $property) {
            if (($column = $this->createPropertyAttribute($metadata, $property, Column::class)) !== null) {
                $metadata->addColumn($column);
            }

            if (($primaryKey = $this->createPropertyAttribute($metadata, $property, PrimaryKey::class)) !== null) {
                $metadata->setPrimaryKey($primaryKey);
                $metadata->getPrimaryKey()->column = $metadata->getColumn($property->getName());
            }

            if (($oneToMany = $this->createPropertyAttribute($metadata, $property, OneToMany::class)) !== null) {
                $metadata->addOneToMany($oneToMany);
            }

            if (($manyToOne = $this->createPropertyAttribute($metadata, $property, ManyToOne::class)) !== null) {
                $metadata->addManyToOne($manyToOne);
            }

            if (($manyToMany = $this->createPropertyAttribute($metadata, $property, ManyToMany::class)) !== null) {
                $metadata->addManyToMany($manyToMany);
            }
        }

        return $metadata;
    }

    /**
     * @param class-string $attributeClass
     *
     * @throws MappingExists
     * @throws MappingNotFound
     * @throws ReflectionException
     */
    private function createPropertyAttribute(
        Metadata $metadata,
        ReflectionProperty $property,
        string $attributeClass
    ): null | PrimaryKey | Column | OneToMany | ManyToMany | ManyToOne {
        if (0 === count($property->getAttributes($attributeClass))) {
            return null;
        }

        $attribute = $property->getAttributes($attributeClass)[0];

        /** @var PrimaryKey|Column|OneToMany|ManyToMany|ManyToOne $propertyAttribute */
        $propertyAttribute = $attribute->newInstance();

        $propertyAttribute->property = $property->getName();

        if ($propertyAttribute instanceof Column && null === $propertyAttribute->name) {
            $propertyAttribute->name = u($property->getName())->lower()->snake()->toString();
        }

        if ($propertyAttribute instanceof ManyToOne) {
            $propertyAttribute->joinColumn = u(
                sprintf(
                    '%s_%s',
                    $this->resolve($propertyAttribute->targetEntity)->getTable()->name,
                    $this->resolve($propertyAttribute->targetEntity)->getPrimaryKey()->column->name
                )
            )->lower()->snake()->toString();
        }

        if ($propertyAttribute instanceof ManyToMany && null === $propertyAttribute->inversedBy) {
            $propertyAttribute->joinTable = u(
                sprintf(
                    '%s_%s',
                    $metadata->getTable()->name,
                    $this->resolve($propertyAttribute->targetEntity)->getTable()->name
                )
            )->lower()->snake()->toString();
        }

        return $propertyAttribute;
    }
}
