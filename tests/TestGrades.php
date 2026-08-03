<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\GradesController;
use App\Repository\GradesRepository;
use App\Service\GradesService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestGrades extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(GradesRepository::class);
        $this->service = $this->createMock(GradesService::class);
        $this->pdo = $this->createMock(\PDO::class);

        $this->controller = new GradesController($this->repository, $this->service, $this->pdo);
    }

    public function testGetGrades()
    {
        $this->repository->expects($this->once())
            ->method('getAllGrades')
            ->willReturn([
                ['id' => 1, 'name' => 'Grade 1'],
                ['id' => 2, 'name' => 'Grade 2'],
            ]);

        $response = $this->controller->getGrades();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPostGrade()
    {
        $request = new Request([], [], ['name' => 'Grade 3']);
        $this->repository->expects($this->once())
            ->method('createGrade')
            ->with(['name' => 'Grade 3'])
            ->willReturn(['id' => 3, 'name' => 'Grade 3']);

        $response = $this->controller->postGrade($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPutGrade()
    {
        $request = new Request([], [], ['id' => 1, 'name' => 'Updated Grade 1']);
        $this->repository->expects($this->once())
            ->method('updateGrade')
            ->with(['id' => 1, 'name' => 'Updated Grade 1'])
            ->willReturn(['id' => 1, 'name' => 'Updated Grade 1']);

        $response = $this->controller->putGrade($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteGrade()
    {
        $request = new Request([], [], ['id' => 1]);
        $this->repository->expects($this->once())
            ->method('deleteGrade')
            ->with(1);

        $response = $this->controller->deleteGrade($request);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// GradesController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\GradesRepository;
use App\Service\GradesService;

class GradesController
{
    private $repository;
    private $service;
    private $pdo;

    public function __construct(GradesRepository $repository, GradesService $service, \PDO $pdo)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->pdo = $pdo;
    }

    public function getGrades()
    {
        $grades = $this->repository->getAllGrades();
        return new Response(json_encode($grades), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function postGrade(Request $request)
    {
        $grade = $this->repository->createGrade($request->request->all());
        return new Response(json_encode($grade), Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    public function putGrade(Request $request)
    {
        $grade = $this->repository->updateGrade($request->request->all());
        return new Response(json_encode($grade), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function deleteGrade(Request $request)
    {
        $this->repository->deleteGrade($request->request->get('id'));
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}