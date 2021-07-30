<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\DependencyInjection;

interface TaggedServicesInterface
{
    /**
     * @return array<array-key, string>
     */
    public static function getTaggedServices(): array;
}
