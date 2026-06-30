<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\طلابController;
use App\Repository\طلابRepository;
use App\Entity\طلاب;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;

class Testطلاب extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $router;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(طلابRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->request = $this->createMock(Request::class);

        $this->controller = new طلابController($this->repository, $this->entityManager, $this->router);
    }

    public function testGetAll()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $response = $this->controller->getAll($this->request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetOne()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new طلاب());

        $response = $this->controller->getOne($this->request, 1);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('create')
            ->with(new طلاب())
            ->willReturn(new طلاب());

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with(new طلاب());

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $response = $this->controller->create($this->request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new طلاب());

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with(new طلاب());

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $response = $this->controller->update($this->request, 1);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new طلاب());

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with(new طلاب());

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $response = $this->controller->delete($this->request, 1);
        $this->assertEquals($expectedResponse, $response);
    }
}


This test file covers the CRUD operations for the 'طلاب' module. It uses mocked PDO statements to simulate database interactions. The tests verify that the correct responses are returned for each operation.