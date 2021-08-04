<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Entity;

use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\Column;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\Entity;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\ManyToMany;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\PrimaryKey;

#[Entity]
class Garply
{
    #[PrimaryKey(autoIncrement: true)]
    #[Column(type: Column::INTEGER)]
    private ?int $id = null;

    #[Column(type: Column::STRING)]
    private string $name;

    /**
     * @var array<array-key, Foo>
     */
    #[ManyToMany(targetEntity: Foo::class, mappedBy: 'garplies')]
    private array $foos = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array<array-key, Foo>
     */
    public function getFoos(): array
    {
        return $this->foos;
    }
}
