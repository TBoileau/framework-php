<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\ORM\QueryBuilder\Expression;

use Stringable;

final class Limit implements Stringable
{
    public function __construct(private int $offset, private int $length)
    {
    }

    public function __toString(): string
    {
        return sprintf('%s, %s', $this->offset, $this->length);
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLength(): int
    {
        return $this->length;
    }
}
