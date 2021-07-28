<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class PostController
{
    public function home(Request $request): Response
    {
        return new Response('Hello world !');
    }
}
