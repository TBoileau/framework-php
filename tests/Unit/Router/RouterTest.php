<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\Router;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TBoileau\Oc\Php\Project5\Router\Exception\RouteNotFound;
use TBoileau\Oc\Php\Project5\Router\Route;
use TBoileau\Oc\Php\Project5\Router\RouterInterface;
use TBoileau\Oc\Php\Project5\Tests\KernelTestCase;
use TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Controller\FooController;

final class RouterTest extends KernelTestCase
{
    private RouterInterface $router;

    protected function setUp(): void
    {
        $kernel = static::bootKernel();
        $this->router = $kernel->getContainer()->get(RouterInterface::class);
    }

    /**
     * @test
     */
    public function ifRouterUrlGeneratorWorks(): void
    {
        $this->router->add(Route::create('bar', '/', FooController::class, 'bar'));

        $this->router->add(
            Route::create(
                'quux',
                '/quux',
                FooController::class,
                'quux',
            )
        );

        $this->assertInstanceOf(
            RedirectResponse::class,
            $this->router->run(
                Request::create($this->router->generateUrl('quux'))
            )
        );
    }

    /**
     * @test
     */
    public function ifRouteNotFoundWhenGenerateUrl(): void
    {
        $this->expectException(RouteNotFound::class);

        $this->router->generateUrl('fail');
    }

    /**
     * @test
     */
    public function ifRouteNotFound(): void
    {
        $this->expectException(RouteNotFound::class);

        $this->router->run(Request::create('/'));
    }

    /**
     * @test
     */
    public function ifRouteWithoutParameterIsFound(): void
    {
        $this->router->add(Route::create('bar', '/', FooController::class, 'bar'));

        $this->assertInstanceOf(Response::class, $this->router->run(Request::create('/')));
    }

    /**
     * @test
     */
    public function ifRouteWithBadParameter(): void
    {
        $this->router->add(
            Route::create(
                'baz',
                '/baz/:qux',
                FooController::class,
                'baz',
                ['qux' => '\d+']
            )
        );

        $this->expectException(RouteNotFound::class);

        $this->router->run(Request::create('/baz/corge'));
    }

    /**
     * @test
     */
    public function ifRouteWithParametersWithoutRequirementsIsFound(): void
    {
        $this->router->add(
            Route::create(
                'baz',
                '/baz/:qux',
                FooController::class,
                'baz',
            )
        );

        $response = $this->router->run(Request::create('/baz/corge'));

        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('corge', $response->getContent());
    }

    /**
     * @test
     */
    public function ifRouteWithParametersAndRequirementsIsFound(): void
    {
        $this->router->add(
            Route::create(
                'baz',
                '/baz/:qux',
                FooController::class,
                'baz',
                ['qux' => '\w+']
            )
        );

        $response = $this->router->run(Request::create('/baz/corge'));

        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('corge', $response->getContent());
    }
}
