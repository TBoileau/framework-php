<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\DependencyInjection;

interface ServiceSubscriberInterface
{
    public function setContainer(\Psr\Container\ContainerInterface $container): void;

    /**
     * @return array<array-key, class-string>
     */
    public static function getSubscribedServices(): array;
}
