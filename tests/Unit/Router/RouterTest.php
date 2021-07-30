<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit\Router;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TBoileau\Oc\Php\Project5\DependencyInjection\Container;
use TBoileau\Oc\Php\Project5\Router\Exception\RouteNotFound;
use TBoileau\Oc\Php\Project5\Router\Route;
use TBoileau\Oc\Php\Project5\Router\Router;
use TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Controller\FooController;

final class RouterTest extends TestCase
{
    /**
     * @test
     */
    public function ifRouteNotFound(): void
    {
        $container = new Container();

        $router = new Router($container);

        $this->expectException(RouteNotFound::class);

        $router->run(Request::create('/'));
    }

    /**
     * @test
     */
    public function ifRouteWithoutParameterIsFound(): void
    {
        $container = new Container();

        $router = new Router($container);
        $router->add(Route::create('foo', '/', FooController::class, 'bar'));

        $this->assertInstanceOf(Response::class, $router->run(Request::create('/')));
    }

    /**
     * @test
     */
    public function ifRouteWithBadParameter(): void
    {
        $container = new Container();

        $router = new Router($container);
        $router->add(
            Route::create(
                'baz',
                '/baz/:qux',
                FooController::class,
                'baz',
                ['qux' => '\d+']
            )
        );

        $this->expectException(RouteNotFound::class);

        $router->run(Request::create('/baz/corge'));
    }

    /**
     * @test
     */
    public function ifRouteWithParametersIsFound(): void
    {
        $container = new Container();

        $router = new Router($container);
        $router->add(
            Route::create(
                'baz',
                '/baz/:qux',
                FooController::class,
                'baz',
                ['qux' => '\w+', 'quux' => '\w+']
            )
        );

        $response = $router->run(Request::create('/baz/corge'));

        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('corge', $response->getContent());
    }
}
