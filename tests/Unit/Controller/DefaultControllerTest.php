<?php

namespace Unit\Controller;

use App\Controller\DefaultController;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class DefaultControllerTest extends KernelTestCase
{
    public function testIndex(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $controller = $container->get(DefaultController::class);

        $response = $controller->index();

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testSetupCheck(): void
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->atLeastOnce())
            ->method('get')
            ->with('testParam')
            ->willReturn(1);

        $controller = new DefaultController();

        $response = $controller->setupCheck($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}