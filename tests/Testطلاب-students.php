<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\StudentsController;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestطلابStudents extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new StudentsController($this->pdoMock);
    }

    public function testGetStudents()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM students')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->getStudents();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateStudent()
    {
        $student = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO students (name, email) VALUES (:name, :email)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->createStudent($student);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateStudent()
    {
        $student = [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE students SET name = :name, email = :email WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->updateStudent($student);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteStudent()
    {
        $id = 1;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM students WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->deleteStudent($id);
        $this->assertEquals(200, $response->getStatusCode());
    }
}



// App\Controller\StudentsController.php

namespace App\Controller;

use PDO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StudentsController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getStudents(): Response
    {
        $stmt = $this->pdo->query('SELECT * FROM students');
        $students = $stmt->fetchAll();

        return new JsonResponse($students);
    }

    public function createStudent(array $student): Response
    {
        $stmt = $this->pdo->prepare('INSERT INTO students (name, email) VALUES (:name, :email)');
        $stmt->execute($student);

        return new JsonResponse(['message' => 'Student created successfully'], 201);
    }

    public function updateStudent(array $student): Response
    {
        $stmt = $this->pdo->prepare('UPDATE students SET name = :name, email = :email WHERE id = :id');
        $stmt->execute($student);

        return new JsonResponse(['message' => 'Student updated successfully'], 200);
    }

    public function deleteStudent(int $id): Response
    {
        $stmt = $this->pdo->prepare('DELETE FROM students WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return new JsonResponse(['message' => 'Student deleted successfully'], 200);
    }
}