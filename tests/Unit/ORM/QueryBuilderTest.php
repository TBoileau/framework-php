<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\ORM;

use TBoileau\Oc\Php\Project5\ORM\Parser\ParserInterface;
use TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression\AndX;
use TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression\OrX;
use TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression\Predicate;
use TBoileau\Oc\Php\Project5\ORM\QueryBuilder\QueryBuilder;
use TBoileau\Oc\Php\Project5\Tests\KernelTestCase;
use TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Entity\Foo;

final class QueryBuilderTest extends KernelTestCase
{
    /**
     * @test
     */
    public function ifQueryWithCompositesIsValid(): void
    {
        $kernel = static::bootKernel();

        /** @var ParserInterface $parser */
        $parser = $kernel->getContainer()->get(ParserInterface::class);

        $queryBuilder = (new QueryBuilder($parser))
            ->select('f')
            ->addSelect('g')
            ->from(Foo::class, 'f')
            ->leftJoin('f.grault', 'g')
            ->where(
                new AndX(
                    'f.bar LIKE :bar',
                    new OrX(
                        'f.quux > NOW()',
                        'f.corge < NOW()'
                    )
                )
            )
            ->setParameter('bar', 'bar')
            ->orderBy('f.quux', 'DESC')
            ->limit(0, 9);

        $this->assertEquals('f', $queryBuilder->getSelects()[0]->getProperty());
        $this->assertNull($queryBuilder->getSelects()[0]->getAlias());

        $this->assertEquals('g', $queryBuilder->getSelects()[1]->getProperty());
        $this->assertNull($queryBuilder->getSelects()[1]->getAlias());

        $this->assertEquals('f', $queryBuilder->getFrom()->getAlias());
        $this->assertEquals(Foo::class, $queryBuilder->getFrom()->getEntity());

        $this->assertCount(0, $queryBuilder->getInnerJoins());
        $this->assertCount(1, $queryBuilder->getLeftJoins());

        $this->assertEquals('g', $queryBuilder->getLeftJoins()[0]->getAlias());
        $this->assertEquals('f.grault', $queryBuilder->getLeftJoins()[0]->getRelation());

        $this->assertInstanceOf(AndX::class, $queryBuilder->getWhere());
        $this->assertInstanceOf(Predicate::class, $queryBuilder->getWhere()->getParts()[0]);
        $this->assertEquals('f.bar LIKE :bar', $queryBuilder->getWhere()->getParts()[0]->getPredicate());
        $this->assertInstanceOf(OrX::class, $queryBuilder->getWhere()->getParts()[1]);
        $this->assertInstanceOf(Predicate::class, $queryBuilder->getWhere()->getParts()[1]->getParts()[0]);
        $this->assertEquals('f.quux > NOW()', $queryBuilder->getWhere()->getParts()[1]->getParts()[0]->getPredicate());
        $this->assertInstanceOf(Predicate::class, $queryBuilder->getWhere()->getParts()[1]->getParts()[1]);
        $this->assertEquals('f.corge < NOW()', $queryBuilder->getWhere()->getParts()[1]->getParts()[1]->getPredicate());

        $this->assertNull($queryBuilder->getGroupBy());

        $this->assertEquals('f.quux', $queryBuilder->getOrderBy()->getProperty());
        $this->assertEquals('DESC', $queryBuilder->getOrderBy()->getDirection());

        $this->assertEquals(0, $queryBuilder->getLimit()->getOffset());
        $this->assertEquals(9, $queryBuilder->getLimit()->getLength());

        $this->assertCount(1, $queryBuilder->getParameters());
        $this->assertEquals(['bar' => 'bar'], $queryBuilder->getParameters());

        $query = $queryBuilder->getQuery();

        $this->assertEquals(
            sprintf(
                'SELECT f,g FROM %s AS f LEFT JOIN f.grault AS g WHERE (f.bar LIKE :bar AND (f.quux > NOW() OR f.corge < NOW())) ORDER BY f.quux DESC LIMIT 0, 9',
                Foo::class
            ),
            $query->getDql()
        );
    }

    /**
     * @test
     */
    public function ifComplexQueryIsValid(): void
    {
        $kernel = static::bootKernel();

        /** @var ParserInterface $parser */
        $parser = $kernel->getContainer()->get(ParserInterface::class);

        $queryBuilder = (new QueryBuilder($parser))
            ->select('f.id')
            ->addSelect('COUNT(DISTINCT g.id)', 'number_of_garplies')
            ->from(Foo::class, 'f')
            ->innerJoin('f.garplies', 'g')
            ->where('f.bar LIKE :bar')
            ->setParameters(['bar' => '%b%'])
            ->groupBy('f.id');

        $this->assertEquals('f.id', $queryBuilder->getGroupBy()->getProperty());

        $query = $queryBuilder->getQuery();

        $this->assertEquals(
            sprintf(
                'SELECT f.id,COUNT(DISTINCT g.id) AS number_of_garplies FROM %s AS f INNER JOIN f.garplies AS g WHERE f.bar LIKE :bar GROUP BY f.id',
                Foo::class
            ),
            $query->getDql()
        );
    }
}
