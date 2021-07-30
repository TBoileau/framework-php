<?php

declare(strict_types=1);

namespace TBoileau\Oc\Php\Project5\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Request;
use TBoileau\Oc\Php\Project5\DependencyInjection\ContainerInterface;
use TBoileau\Oc\Php\Project5\Kernel;
use TBoileau\Oc\Php\Project5\Router\Route;
use TBoileau\Oc\Php\Project5\Router\RouterInterface;
use TBoileau\Oc\Php\Project5\Tests\Unit\Fixtures\Controller\FooController;

final class KernelTest extends TestCase
{
    /**
     * @test
     */
    public function ifHomeRouteSendHelloWorld(): void
    {
        $kernel = new Kernel($_ENV['APP_ENV'], Request::create('/'));

        $reflectionProperty = new ReflectionProperty(Kernel::class, 'container');
        $reflectionProperty->setAccessible(true);

        /** @var ContainerInterface $container */
        $container = $reflectionProperty->getValue($kernel);

        /** @var RouterInterface $router */
        $router = $container->get(RouterInterface::class);

        $router->add(Route::create('bar', '/', FooController::class, 'bar'));

        ob_start();
        $kernel->run();
        $response = ob_get_clean();

        $this->assertStringContainsString('Hello world !', $response);
    }
}
