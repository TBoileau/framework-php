<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\QueryBuilder;

use TBoileau\Oc\Php\Project5\ORM\Parser\ParserInterface;

final class Query
{
    public function __construct(private ParserInterface $parser, private QueryBuilder $queryBuilder)
    {
    }

    public function getDql(): string
    {
        $dql = sprintf('SELECT %s', implode(',', $this->queryBuilder->getSelects()));

        $dql .= sprintf(' FROM %s', $this->queryBuilder->getFrom());

        if (count($this->queryBuilder->getInnerJoins()) > 0) {
            $dql .= sprintf(' INNER JOIN %s', implode(',', $this->queryBuilder->getInnerJoins()));
        }

        if (count($this->queryBuilder->getLeftJoins()) > 0) {
            $dql .= sprintf(' LEFT JOIN %s', implode(',', $this->queryBuilder->getLeftJoins()));
        }

        if (null !== $this->queryBuilder->getWhere()) {
            $dql .= sprintf(' WHERE %s', $this->queryBuilder->getWhere());
        }

        if (null !== $this->queryBuilder->getGroupBy()) {
            $dql .= sprintf(' GROUP BY %s', $this->queryBuilder->getGroupBy());
        }

        if (null !== $this->queryBuilder->getOrderBy()) {
            $dql .= sprintf(' ORDER BY %s', $this->queryBuilder->getOrderBy());
        }

        if (null !== $this->queryBuilder->getLimit()) {
            $dql .= sprintf(' LIMIT %s', $this->queryBuilder->getLimit());
        }

        return $dql;
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * @return array<array-key, array<string, mixed>|object>
     */
    public function getResult(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>|object
     */
    public function getSingleResult(): array | object
    {
        return [];
    }

    private function getSQL(): string
    {
        return $this->parser->parse($this->queryBuilder);
    }
}
