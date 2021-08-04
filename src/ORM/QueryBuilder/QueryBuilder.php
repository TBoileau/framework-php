<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\QueryBuilder;

use TBoileau\Oc\Php\Project5\ORM\Parser\ParserInterface;
use TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression\Composite;
use TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression\From;
use TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression\GroupBy;
use TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression\Join;
use TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression\Limit;
use TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression\OrderBy;
use TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression\Predicate;
use TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression\Select;

final class QueryBuilder
{
    /**
     * @var array<array-key, Select>
     */
    private array $selects = [];

    private From $from;

    /**
     * @var array<array-key, Join>
     */
    private array $innerJoins = [];

    /**
     * @var array<array-key, Join>
     */
    private array $leftJoins = [];

    private null | Composite | Predicate $where = null;

    private ?GroupBy $groupBy = null;

    private ?OrderBy $orderBy = null;

    private ?Limit $limit = null;

    /**
     * @var array<string, mixed>
     */
    private array $parameters = [];

    public function __construct(private ParserInterface $parser)
    {
    }

    public function select(string $property, ?string $alias = null): QueryBuilder
    {
        $this->selects[] = new Select($property, $alias);

        return $this;
    }

    public function addSelect(string $property, ?string $alias = null): QueryBuilder
    {
        $this->selects[] = new Select($property, $alias);

        return $this;
    }

    public function from(string $entity, string $alias): QueryBuilder
    {
        $this->from = new From($entity, $alias);

        return $this;
    }

    public function innerJoin(string $relation, string $alias): QueryBuilder
    {
        $this->innerJoins[] = new Join($relation, $alias);

        return $this;
    }

    public function leftJoin(string $relation, string $alias): QueryBuilder
    {
        $this->leftJoins[] = new Join($relation, $alias);

        return $this;
    }

    public function where(Composite | Predicate | string $where): QueryBuilder
    {
        if (is_string($where)) {
            $where = new Predicate($where);
        }

        $this->where = $where;

        return $this;
    }

    public function groupBy(string $property): QueryBuilder
    {
        $this->groupBy = new GroupBy($property);

        return $this;
    }

    public function limit(int $offset, int $length): QueryBuilder
    {
        $this->limit = new Limit($offset, $length);

        return $this;
    }

    public function orderBy(string $property, string $direction): QueryBuilder
    {
        $this->orderBy = new OrderBy($property, $direction);

        return $this;
    }

    public function setParameter(string $name, mixed $value): QueryBuilder
    {
        $this->parameters[$name] = $value;

        return $this;
    }

    /**
     * @param array<string, mixed> $parameters
     */
    public function setParameters(array $parameters): QueryBuilder
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return array<array-key, Select>
     */
    public function getSelects(): array
    {
        return $this->selects;
    }

    public function getFrom(): From
    {
        return $this->from;
    }

    /**
     * @return array<array-key, Join>
     */
    public function getInnerJoins(): array
    {
        return $this->innerJoins;
    }

    /**
     * @return array<array-key, Join>
     */
    public function getLeftJoins(): array
    {
        return $this->leftJoins;
    }

    public function getWhere(): Predicate | Composite | null
    {
        return $this->where;
    }

    public function getGroupBy(): ?GroupBy
    {
        return $this->groupBy;
    }

    public function getOrderBy(): ?OrderBy
    {
        return $this->orderBy;
    }

    public function getLimit(): ?Limit
    {
        return $this->limit;
    }

    /**
     * @return array<string, mixed>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getQuery(): Query
    {
        return new Query($this->parser, $this);
    }
}
