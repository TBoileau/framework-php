<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class FooController
{
    public function bar(): Response
    {
        return new Response('Hello world !');
    }

    public function baz(Request $request, string $qux): Response
    {
        return new Response($qux);
    }
}
