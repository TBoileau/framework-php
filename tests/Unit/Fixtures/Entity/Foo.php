<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Entity;

use DateTimeImmutable;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\Column;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\Entity;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\ManyToMany;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\ManyToOne;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\PrimaryKey;
use TBoileau\Oc\Php\Project5\ORM\Mapping\Attribute\Table;
use TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Repository\FooRepository;

#[Entity(repositoryClass: FooRepository::class)]
#[Table(name: 'foo_table')]
class Foo
{
    #[PrimaryKey(autoIncrement: true)]
    #[Column(type: Column::INTEGER)]
    private ?int $id = null;

    #[Column(name: 'bar_column', type: Column::STRING, unique: true)]
    private string $bar;

    #[Column(type: Column::FLOAT, nullable: true, precision: 5, scale: 4)]
    private ?float $baz = null;

    #[Column(type: Column::ARRAY)]
    private array $qux = [];

    #[Column(type: Column::DATE)]
    private DateTimeImmutable $quux;

    #[Column(type: Column::DATETIME)]
    private DateTimeImmutable $corge;

    #[ManyToOne(targetEntity: Grault::class, nullable: true, inversedBy: 'foos')]
    private ?Grault $grault = null;

    /**
     * @var array<array-key, Garply>
     */
    #[ManyToMany(targetEntity: Garply::class, inversedBy: 'foos', joinTable: 'foo_garplies')]
    private array $garplies = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBar(): string
    {
        return $this->bar;
    }

    public function setBar(string $bar): void
    {
        $this->bar = $bar;
    }

    public function getBaz(): ?float
    {
        return $this->baz;
    }

    public function setBaz(?float $baz): void
    {
        $this->baz = $baz;
    }

    public function getQux(): array
    {
        return $this->qux;
    }

    public function setQux(array $qux): void
    {
        $this->qux = $qux;
    }

    public function getQuux(): DateTimeImmutable
    {
        return $this->quux;
    }

    public function setQuux(DateTimeImmutable $quux): void
    {
        $this->quux = $quux;
    }

    public function getCorge(): DateTimeImmutable
    {
        return $this->corge;
    }

    public function setCorge(DateTimeImmutable $corge): void
    {
        $this->corge = $corge;
    }

    public function getGrault(): ?Grault
    {
        return $this->grault;
    }

    public function setGrault(?Grault $grault): void
    {
        $this->grault = $grault;
    }

    /**
     * @return array<array-key, Garply>
     */
    public function getGarplies(): array
    {
        return $this->garplies;
    }
}
