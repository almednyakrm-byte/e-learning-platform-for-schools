<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\Magrat;
use App\Repository\MagratRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestMagrat extends TestCase
{
    private $magratController;
    private $magratRepository;

    protected function setUp(): void
    {
        $this->magratRepository = $this->createMock(MagratRepository::class);
        $this->magratController = new Magrat($this->magratRepository);
    }

    public function testGetAllMagrats()
    {
        $this->magratRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Magrat 1'],
                ['id' => 2, 'name' => 'Magrat 2'],
            ]);

        $request = new Request();
        $response = $this->magratController->getAllMagrats($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateMagrat()
    {
        $this->magratRepository->expects($this->once())
            ->method('create')
            ->with(['name' => 'New Magrat'])
            ->willReturn(['id' => 3, 'name' => 'New Magrat']);

        $request = new Request([], [], ['name' => 'New Magrat']);
        $response = $this->magratController->createMagrat($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateMagrat()
    {
        $this->magratRepository->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'Updated Magrat'])
            ->willReturn(['id' => 1, 'name' => 'Updated Magrat']);

        $request = new Request([], [], ['name' => 'Updated Magrat']);
        $response = $this->magratController->updateMagrat(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteMagrat()
    {
        $this->magratRepository->expects($this->once())
            ->method('delete')
            ->with(1);

        $request = new Request();
        $response = $this->magratController->deleteMagrat(1, $request);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file includes four test methods:

- `testGetAllMagrats`: Tests the `getAllMagrats` method of the `MagratController` class. It expects the `findAll` method of the `MagratRepository` to be called once and returns a list of magrats.
- `testCreateMagrat`: Tests the `createMagrat` method of the `MagratController` class. It expects the `create` method of the `MagratRepository` to be called once with the provided magrat data and returns the created magrat.
- `testUpdateMagrat`: Tests the `updateMagrat` method of the `MagratController` class. It expects the `update` method of the `MagratRepository` to be called once with the provided magrat data and returns the updated magrat.
- `testDeleteMagrat`: Tests the `deleteMagrat` method of the `MagratController` class. It expects the `delete` method of the `MagratRepository` to be called once with the provided magrat ID.

Each test method uses the `createMock` method to create a mock object for the `MagratRepository` class and sets up the expected behavior for the `findAll`, `create`, `update`, and `delete` methods. The test then calls the corresponding method of the `MagratController` class and asserts that the expected behavior is met.