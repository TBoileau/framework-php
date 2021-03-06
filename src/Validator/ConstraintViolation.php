<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Validator;

final class ConstraintViolation
{
    public function __construct(private string $path, private string $message)
    {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
