<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TeachersController;
use App\Repository\TeachersRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestTeachers extends TestCase
{
    private $teachersController;
    private $teachersRepository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->teachersRepository = $this->createMock(TeachersRepository::class);
        $this->teachersController = new TeachersController($this->teachersRepository);
    }

    public function testGetTeachers()
    {
        $expectedResponse = ['teachers' => []];
        $this->teachersRepository->expects($this->once())
            ->method('getAll')
            ->willReturn($expectedResponse);
        $response = $this->teachersController->getTeachers();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPostTeacher()
    {
        $teacherData = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $expectedResponse = ['message' => 'Teacher created successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO teachers (name, email) VALUES (:name, :email)');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => $teacherData['name'], 'email' => $teacherData['email']]);
        $response = $this->teachersController->postTeacher($teacherData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPutTeacher()
    {
        $teacherId = 1;
        $teacherData = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $expectedResponse = ['message' => 'Teacher updated successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE teachers SET name = :name, email = :email WHERE id = :id');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => $teacherData['name'], 'email' => $teacherData['email'], 'id' => $teacherId]);
        $response = $this->teachersController->putTeacher($teacherId, $teacherData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteTeacher()
    {
        $teacherId = 1;
        $expectedResponse = ['message' => 'Teacher deleted successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM teachers WHERE id = :id');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['id' => $teacherId]);
        $response = $this->teachersController->deleteTeacher($teacherId);
        $this->assertEquals($expectedResponse, $response);
    }
}