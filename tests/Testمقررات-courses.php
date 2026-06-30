<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MagratheasController;
use App\Repository\MagratheasRepository;
use App\Service\MagratheasService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestMagratheasController extends TestCase
{
    private $magratheasController;
    private $magratheasRepository;
    private $magratheasService;
    private $pdo;

    protected function setUp(): void
    {
        $this->magratheasRepository = $this->createMock(MagratheasRepository::class);
        $this->magratheasService = $this->createMock(MagratheasService::class);
        $this->pdo = $this->createMock(\PDO::class);

        $this->magratheasController = new MagratheasController($this->magratheasRepository, $this->magratheasService, $this->pdo);
    }

    public function testGetMagratheas()
    {
        $this->magratheasRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Course 1'],
                ['id' => 2, 'name' => 'Course 2'],
            ]);

        $response = $this->magratheasController->getMagratheas();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testGetMagratheasById()
    {
        $this->magratheasRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Course 1']);

        $response = $this->magratheasController->getMagratheasById(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testGetMagratheasByIdNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->magratheasRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->magratheasController->getMagratheasById(1);
    }

    public function testCreateMagratheas()
    {
        $this->magratheasService->expects($this->once())
            ->method('create')
            ->with(['name' => 'Course 1'])
            ->willReturn(['id' => 1, 'name' => 'Course 1']);

        $request = new Request([], [], ['name' => 'Course 1']);
        $response = $this->magratheasController->createMagratheas($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testUpdateMagratheas()
    {
        $this->magratheasService->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'Course 1'])
            ->willReturn(['id' => 1, 'name' => 'Course 1']);

        $request = new Request([], [], ['name' => 'Course 1']);
        $response = $this->magratheasController->updateMagratheas(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testDeleteMagratheas()
    {
        $this->magratheasService->expects($this->once())
            ->method('delete')
            ->with(1);

        $response = $this->magratheasController->deleteMagratheas(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}