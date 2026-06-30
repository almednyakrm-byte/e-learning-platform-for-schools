<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ProfessorController;
use App\Repository\ProfessorRepository;
use App\Entity\Professor;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestProfessorController extends TestCase
{
    private $controller;
    private $repository;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ProfessorRepository::class);
        $this->controller = new ProfessorController($this->repository);
        $this->request = $this->createMock(Request::class);
    }

    public function testGetProfessors()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new Professor()]);

        $response = $this->controller->getProfessors($this->request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetProfessor()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new Professor());

        $this->request->expects($this->once())
            ->method('attributes')
            ->willReturn(['id' => $id]);

        $response = $this->controller->getProfessor($this->request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetProfessorNotFound()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->request->expects($this->once())
            ->method('attributes')
            ->willReturn(['id' => $id]);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->getProfessor($this->request);
    }

    public function testCreateProfessor()
    {
        $professor = new Professor();
        $professor->setName('John Doe');
        $professor->setEmail('john.doe@example.com');

        $this->repository->expects($this->once())
            ->method('save')
            ->with($professor);

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'John Doe', 'email' => 'john.doe@example.com']);

        $response = $this->controller->createProfessor($this->request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateProfessor()
    {
        $id = 1;
        $professor = new Professor();
        $professor->setName('John Doe');
        $professor->setEmail('john.doe@example.com');

        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($professor);

        $this->request->expects($this->once())
            ->method('attributes')
            ->willReturn(['id' => $id]);

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'John Doe', 'email' => 'john.doe@example.com']);

        $response = $this->controller->updateProfessor($this->request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteProfessor()
    {
        $id = 1;

        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new Professor());

        $this->request->expects($this->once())
            ->method('attributes')
            ->willReturn(['id' => $id]);

        $response = $this->controller->deleteProfessor($this->request);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}