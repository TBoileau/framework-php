<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TBoileau\Oc\Php\Project5\Controller\AbstractController;

final class FooController extends AbstractController
{
    public function bar(): Response
    {
        return $this->render('bar.html.twig');
    }

    public function baz(Request $request, string $qux): Response
    {
        return $this->render('baz.html.twig', ['qux' => $qux]);
    }

    public function quux(): RedirectResponse
    {
        return $this->redirect('bar');
    }
}
