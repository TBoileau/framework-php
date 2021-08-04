<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\Mapping;

use ReflectionClass;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\Column;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\Entity;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\ManyToMany;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\ManyToOne;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\OneToMany;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\PrimaryKey;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\Table;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Exception\MappingExists;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Exception\MappingNotFound;

final class Metadata
{
    private ReflectionClass $class;

    private Entity $entity;

    private Table $table;

    private PrimaryKey $primaryKey;

    /**
     * @var array<array-key, Column>
     */
    private array $columns = [];

    /**
     * @var array<array-key, OneToMany>
     */
    private array $oneToManys = [];

    /**
     * @var array<array-key, ManyToMany>
     */
    private array $manyToManys = [];

    /**
     * @var array<array-key, ManyToOne>
     */
    private array $manyToOnes = [];

    public static function create(ReflectionClass $class): Metadata
    {
        $metadata = new self();
        $metadata->class = $class;

        return $metadata;
    }

    public function setEntity(Entity $entity): Metadata
    {
        $this->entity = $entity;

        return $this;
    }

    public function setTable(Table $table): Metadata
    {
        $this->table = $table;

        return $this;
    }

    public function setPrimaryKey(PrimaryKey $primaryKey): Metadata
    {
        $this->primaryKey = $primaryKey;

        return $this;
    }

    public function addColumn(Column $column): Metadata
    {
        if ($this->hasColumn($column->property)) {
            throw new MappingExists(sprintf('Column mapping for %s already exists.', $column->property));
        }

        $this->columns[] = $column;

        return $this;
    }

    public function addOneToMany(OneToMany $oneToMany): Metadata
    {
        if ($this->hasOneToMany($oneToMany->property)) {
            throw new MappingExists(sprintf('OneToMany mapping for %s already exists.', $oneToMany->property));
        }

        $this->oneToManys[] = $oneToMany;

        return $this;
    }

    public function addManyToMany(ManyToMany $manyToMany): Metadata
    {
        if ($this->hasManyToMany($manyToMany->property)) {
            throw new MappingExists(sprintf('ManyToMany mapping for %s already exists.', $manyToMany->property));
        }

        $this->manyToManys[] = $manyToMany;

        return $this;
    }

    public function addManyToOne(ManyToOne $manyToOne): Metadata
    {
        if ($this->hasManyToOne($manyToOne->property)) {
            throw new MappingExists(sprintf('ManyToOne mapping for %s already exists.', $manyToOne->property));
        }

        $this->manyToOnes[] = $manyToOne;

        return $this;
    }

    public function getClass(): ReflectionClass
    {
        return $this->class;
    }

    public function getEntity(): Entity
    {
        return $this->entity;
    }

    public function getTable(): Table
    {
        return $this->table;
    }

    public function getPrimaryKey(): PrimaryKey
    {
        return $this->primaryKey;
    }

    /**
     * @return array<array-key, Column>
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getColumn(string $property): Column
    {
        if (!$this->hasColumn($property)) {
            throw new MappingNotFound(sprintf('Column mapping for property %s does not exist.', $property));
        }

        return $this->searchColumn($property);
    }

    /**
     * @return array<array-key, OneToMany>
     */
    public function getOneToManys(): array
    {
        return $this->oneToManys;
    }

    public function getOneToMany(string $property): OneToMany
    {
        if (!$this->hasOneToMany($property)) {
            throw new MappingNotFound(sprintf('OneToMany mapping for property %s does not exist.', $property));
        }

        return $this->searchOneToMany($property);
    }

    /**
     * @return array<array-key, ManyToMany>
     */
    public function getManyToManys(): array
    {
        return $this->manyToManys;
    }

    public function getManyToMany(string $property): ManyToMany
    {
        if (!$this->hasManyToMany($property)) {
            throw new MappingNotFound(sprintf('ManyToMany mapping for property %s does not exist.', $property));
        }

        return $this->searchManyToMany($property);
    }

    /**
     * @return array<array-key, ManyToOne>
     */
    public function getManyToOnes(): array
    {
        return $this->manyToOnes;
    }

    public function getManyToOne(string $property): ManyToOne
    {
        if (!$this->hasManyToOne($property)) {
            throw new MappingNotFound(sprintf('ManyToOne mapping for property %s does not exist.', $property));
        }

        return $this->searchManyToOne($property);
    }

    private function hasColumn(string $property): bool
    {
        return null !== $this->searchColumn($property);
    }

    private function searchColumn(string $property): ?Column
    {
        $filteredColumns = array_values(array_filter(
            $this->columns,
            static fn (Column $column) => $column->property === $property
        ));

        return $filteredColumns[0] ?? null;
    }

    private function hasManyToMany(string $property): bool
    {
        return null !== $this->searchManyToMany($property);
    }

    private function searchManyToMany(string $property): ?ManyToMany
    {
        $filteredManyToManys = array_values(array_filter(
            $this->manyToManys,
            static fn (ManyToMany $manyToMany) => $manyToMany->property === $property
        ));

        return $filteredManyToManys[0] ?? null;
    }

    private function hasOneToMany(string $property): bool
    {
        return null !== $this->searchOneToMany($property);
    }

    private function searchOneToMany(string $property): ?OneToMany
    {
        $filteredOneToManys = array_values(array_filter(
            $this->oneToManys,
            static fn (OneToMany $oneToMany) => $oneToMany->property === $property
        ));

        return $filteredOneToManys[0] ?? null;
    }

    private function hasManyToOne(string $property): bool
    {
        return null !== $this->searchManyToOne($property);
    }

    private function searchManyToOne(string $property): ?ManyToOne
    {
        $filteredManyToOnes = array_values(array_filter(
            $this->manyToOnes,
            static fn (ManyToOne $manyToOne) => $manyToOne->property === $property
        ));

        return $filteredManyToOnes[0] ?? null;
    }
}
