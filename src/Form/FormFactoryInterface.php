<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Form;

interface FormFactoryInterface
{
    public function create(string $name, object $data): FormInterface;
}
